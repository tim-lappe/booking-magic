<?php

namespace TLBM\Calendar;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Database\Contracts\ORMInterface;
use TLBM\Entity\Calendar;

if ( !defined('ABSPATH')) {
    return;
}

class CalendarManager implements CalendarManagerInterface
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
     * @param Calendar $calendar
     *
     * @throws Exception
     */
    public function saveCalendar(Calendar $calendar)
    {
        $mgr = $this->repository->getEntityManager();
        $mgr->persist($calendar);
        $mgr->flush();
    }

    /**
     * Returns the BookingCalender from the given Post-Id
     *
     * @param mixed $id The Post-Id of the Calendar
     *
     * @return Calendar|null
     */
    public function getCalendar($id): ?Calendar
    {
        try {
            $mgr      = $this->repository->getEntityManager();
            $calendar = $mgr->find("\TLBM\Entity\Calendar", $id);
            if ($calendar instanceof Calendar) {
                return $calendar;
            }
        } catch (Exception $e) {
            var_dump($e);
        }

        return null;
    }

    /**
     * Return a List of all active Calendars
     *
     * @param array $options
     * @param string $orderby
     * @param string $order
     * @param int $offset
     * @param int $limit
     *
     * @return Calendar[]
     */
    public function getAllCalendars(
        array $options = array(),
        string $orderby = "title",
        string $order = "desc",
        int $offset = 0,
        int $limit = 0
    ): array {
        $mgr = $this->repository->getEntityManager();
        $qb  = $mgr->createQueryBuilder();
        $qb->select("c")->from("\TLBM\Entity\Calendar", "c")->orderBy("c." . $orderby, $order)->setFirstResult($offset);
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }

        $query  = $qb->getQuery();
        $result = $query->getResult();

        if (is_array($result)) {
            return $result;
        }

        return array();
    }

    /**
     * @param array $options
     *
     * @return int
     */
    public function getAllCalendarsCount(array $options = array()): int
    {
        $mgr = $this->repository->getEntityManager();
        $qb  = $mgr->createQueryBuilder();
        $qb->select($qb->expr()->count("c"))->from("\TLBM\Entity\Calendar", "c");

        $query = $qb->getQuery();
        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            return 0;
        }
    }
}