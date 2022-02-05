<?php


namespace TLBM\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Throwable;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\ExpiryTime;
use TLBM\Entity\Booking;
use TLBM\Repository\Contracts\BookingRepositoryInterface;
use TLBM\Repository\Contracts\ORMInterface;
use TLBM\Utilities\ExtendedDateTime;

use const TLBM\Booking\WP_DEBUG;
use const TLBM_BOOKING_INTERNAL_STATE_PENDING;


if ( !defined('ABSPATH')) {
    return;
}

class BookingRepository implements BookingRepositoryInterface
{

    /**
     * @var ORMInterface
     */
    private ORMInterface $repository;

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    public function __construct(ORMInterface $repository, SettingsManagerInterface $settingsManager)
    {
        $this->repository = $repository;
        $this->settingsManager = $settingsManager;
    }

    /**
     * @param Booking $booking
     *
     * @return int
     * @throws Throwable
     */
    public function saveBooking(Booking $booking): int
    {
        $mgr = $this->repository->getEntityManager();
        $mgr->persist($booking);
        $mgr->flush();

        return $booking->getId();
    }

    /**
     * @param array $options
     * @param string $orderby
     * @param string $order
     * @param int $offset
     * @param int $limit
     *
     * @return Booking[]
     */
    public function getAllBookings(
        array $options = array(),
        string $orderby = "id",
        string $order = "desc",
        int $offset = 0,
        int $limit = 0
    ): array
    {
        $mgr = $this->repository->getEntityManager();
        $queryBuilder  = $mgr->createQueryBuilder();
        $queryBuilder->select("b")->from("\TLBM\Entity\Booking", "b")->orderBy("b." . $orderby, $order)->setFirstResult($offset);
        if ($limit > 0) {
            $queryBuilder->setMaxResults($limit);
        }

        $query  = $queryBuilder->getQuery();
        $result = $query->getResult();

        if (is_array($result)) {
            return $result;
        }

        return array();
    }

    /**
     * @param int $booking_id
     *
     * @return ?Booking
     */
    public function getBooking(int $booking_id): ?Booking
    {
        try {
            $mgr      = $this->repository->getEntityManager();
            $booking = $mgr->find("\TLBM\Entity\Booking", $booking_id);
            if ($booking instanceof Booking) {
                return $booking;
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        return null;
    }

    /**
     *
     * @return void
     */
    public function cleanExpiredReservedBookings()
    {
        try {
            $dateTime   = new ExtendedDateTime();
            $expiryTime = $this->settingsManager->getValue(ExpiryTime::class);
            $dateTime->setMinute($dateTime->getMinute() - $expiryTime);
            $thresholdTsamp = $dateTime->getTimestamp();

            $mgr          = $this->repository->getEntityManager();
            $queryBuilder = $mgr->createQueryBuilder();
            $queryBuilder->select("b")->from("\TLBM\Entity\Booking", "b")->where("b.tstampCreated < :tstamp AND b.internalState = :state")->setParameter("tstamp", $thresholdTsamp)->setParameter("state", TLBM_BOOKING_INTERNAL_STATE_PENDING);

            $result = $queryBuilder->getQuery()->getResult();
            foreach ($result as $r) {
                $mgr->remove($r);
            }

            $mgr->flush();

        } catch (Throwable $exception) {
            if(WP_DEBUG) {
                var_dump($exception->getMessage());
            }
        }
    }

    /**
     * @return int
     */
    public function getAllBookingsCount(): int
    {
        $mgr = $this->repository->getEntityManager();
        $queryBuilder  = $mgr->createQueryBuilder();
        $queryBuilder->select($queryBuilder->expr()->count("b"))->from("\TLBM\Entity\Booking", "b");

        $query = $queryBuilder->getQuery();
        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            return 0;
        }
    }
}