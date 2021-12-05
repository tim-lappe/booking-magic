<?php

namespace TLBM\Calendar;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use TLBM\Database\OrmManager;
use TLBM\Entity\Calendar;

if( ! defined( 'ABSPATH' ) ) {
	return;
}

class CalendarManager {


    /**
     * @param Calendar $calendar
     * @throws Exception
     */
    public static function SaveCalendar( Calendar $calendar ) {
        $mgr = OrmManager::GetEntityManager();
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
	public static function GetCalendar( $id ): ?Calendar {
        try {
            $mgr = OrmManager::GetEntityManager();
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
     * @return Calendar[]
     */
	public static function GetAllCalendars(array $options = array(), string $orderby = "title", string $order = "desc", int $offset = 0, int $limit = 0): array {
        $mgr = OrmManager::GetEntityManager();
        $qb = $mgr->createQueryBuilder();
        $qb ->select("c")
            ->from("\TLBM\Entity\Calendar", "c")
            ->orderBy("c." . $orderby, $order)
            ->setFirstResult($offset);
        if($limit > 0) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $result = $query->getResult();

        if(is_array($result)) {
            return $result;
        }

        return array();
    }

    /**
     * @param array $options
     * @return int
     */
	public static function GetAllCalendarsCount(array $options = array()): int {

        $mgr = OrmManager::GetEntityManager();
        $qb = $mgr->createQueryBuilder();
        $qb ->select($qb->expr()->count("c"))
            ->from("\TLBM\Entity\Calendar", "c");

        $query = $qb->getQuery();
        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }
}