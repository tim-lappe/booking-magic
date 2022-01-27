<?php


namespace TLBM\Calendar;


use Exception;
use TLBM\Calendar\Contracts\CalendarGroupManagerInterface;
use TLBM\Calendar\Contracts\CalendarSelectionHandlerInterface;
use TLBM\Database\Contracts\ORMInterface;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarGroup;

class CalendarGroupManager implements CalendarGroupManagerInterface
{

    private ORMInterface $repository;

    private CalendarSelectionHandlerInterface $calendarSelectionHandler;

    public function __construct(ORMInterface $repository, CalendarSelectionHandler $calendarSelectionHandler)
    {
        $this->repository               = $repository;
        $this->calendarSelectionHandler = $calendarSelectionHandler;
    }

    /**
     * @param CalendarGroup $group
     *
     * @return Calendar[]
     */
    public function getCalendarListFromGroup(CalendarGroup $group): array
    {
        return $this->calendarSelectionHandler->getSelectedCalendarList($group->getCalendarSelection());
    }

    /**
     * Returns the Calendar Group from the given Post-Id
     *
     * @param $id
     *
     * @return ?CalendarGroup
     */
    public function getCalendarGroup($id): ?CalendarGroup
    {
        try {
            $mgr           = $this->repository->getEntityManager();
            $calendarGroup = $mgr->find("\TLBM\Entity\CalendarGroup", $id);
            if ($calendarGroup instanceof CalendarGroup) {
                return $calendarGroup;
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
     *
     * @return CalendarGroup[]
     */
    public function getAllGroups(
        array $options = array(),
        string $orderby = "title",
        string $order = "desc",
        int $offset = 0,
        int $limit = 0
    ): array {
        $mgr = $this->repository->getEntityManager();
        $qb  = $mgr->createQueryBuilder();
        $qb->select("c")->from("\TLBM\Entity\CalendarGroup", "c")->orderBy("c." . $orderby, $order)->setFirstResult($offset);
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }

        $query  = $qb->getQuery();
        $result = $query->getResult();

        $query->free();

        if (is_array($result)) {
            return $result;
        }

        return array();
    }

    public function getAllGroupsCount(array $options = array()): int
    {
        //TODO: Orm Implementation

        $posts = get_posts(
            array_merge(                                                                                                                                                            array(
                                                                                                                                                                                     "post_type"   => TLBM_PT_CALENDAR_GROUPS,
                                                                                                                                                                                     "numberposts" => -1
                                                                                                                                                                                 ), $options)
        );

        return sizeof($posts);
    }
}