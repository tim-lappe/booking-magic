<?php

namespace TLBM\Calendar\Contracts;

use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarGroup;

interface CalendarGroupManagerInterface
{

    /**
     * @param CalendarGroup $group
     *
     * @return Calendar[]
     */
    public function getCalendarListFromGroup(CalendarGroup $group): array;

    /**
     * Returns the Calendar Group from the given Post-Id
     *
     * @param $id
     *
     * @return ?CalendarGroup
     */
    public function getCalendarGroup($id): ?CalendarGroup;

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
    ): array;

    public function getAllGroupsCount(array $options = array()): int;
}