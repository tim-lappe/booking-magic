<?php


namespace TLBM\Calendar;


use Doctrine\ORM\AbstractQuery;
use Exception;
use TLBM\Database\OrmManager;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarGroup;
use TLBM\Model\CalendarSelection;
use WP_Post;

class CalendarGroupManager {


	/**
	 * @param CalendarGroup $group
	 *
	 * @return Calendar[]
	 */
	public static function GetCalendarListFromGroup(CalendarGroup $group): array {
		return CalendarSelectionHandler::GetSelectedCalendarList($group->GetCalendarSelection());
	}

    /**
     * Returns the Calendar Group from the given Post-Id
     *
     * @param $id
     * @return ?CalendarGroup
     */
	public static function GetCalendarGroup( $id ): ?CalendarGroup {
        try {
            $mgr = OrmManager::GetEntityManager();
            $calendar = $mgr->find("\TLBM\Entity\CalendarGroup", $id);
            if ($calendar instanceof Calendar) {
                return $calendar;
            }
        } catch (Exception $e) {
            var_dump($e);
        }
        return null;
	}


    /**
     * Return a List of all active Groups
     *
     * @param array $options
     * @param string $orderby
     * @param string $order
     * @param int $offset
     * @param int $limit
     * @return CalendarGroup[]
     */
	public static function GetAllGroups(array $options = array(), string $orderby = "title", string $order = "desc", int $offset = 0, int $limit = 0): array {
        $mgr = OrmManager::GetEntityManager();
        $qb = $mgr->createQueryBuilder();
        $qb ->select("c")
            ->from("\TLBM\Entity\CalendarGroup", "c")
            ->orderBy("c." . $orderby, $order)
            ->setFirstResult($offset);
        if($limit > 0) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $result = $query->getResult();

        $query->free();

        if(is_array($result)) {
            return $result;
        }

        return array();
	}

	public static function GetAllGroupsCount($get_posts_options = array()): int {
		$posts = get_posts(array_merge(array(
			"post_type" => TLBM_PT_CALENDAR_GROUPS,
			"numberposts" => -1
		), $get_posts_options));

		return sizeof($posts);
	}
}