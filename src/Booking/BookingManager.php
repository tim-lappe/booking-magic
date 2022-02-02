<?php


namespace TLBM\Booking;


use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use TLBM\Booking\Contracts\BookingManagerInterface;
use TLBM\Database\Contracts\ORMInterface;
use TLBM\Entity\Booking;

if ( !defined('ABSPATH')) {
    return;
}

class BookingManager implements BookingManagerInterface
{

    /**
     * @var ORMInterface
     */
    private ORMInterface $repository;

    public function __construct(ORMInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Booking $booking
     *
     * @return int
     * @throws OptimisticLockException
     * @throws ORMException
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
            var_dump($e);
        }

        return null;
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