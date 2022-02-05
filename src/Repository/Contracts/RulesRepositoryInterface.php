<?php

namespace TLBM\Repository\Contracts;

use DateTime;
use Exception;
use TLBM\Entity\Rule;

interface RulesRepositoryInterface
{

    /**
     * Get a Rule
     *
     * @param $rule_id
     *
     * @return false|Rule
     */
    public function getRule($rule_id): ?Rule;

    /**
     * @param Rule $rule
     *
     * @throws Exception
     */
    public function saveRule(Rule $rule);

    /**
     * Get all Rules
     *
     * @param array $options
     * @param string $orderby
     * @param string $order
     * @param int $offset
     * @param int $limit
     *
     * @return Rule[]
     */
    public function getAllRules(
        array $options = array(),
        string $orderby = "title",
        string $order = "desc",
        int $offset = 0,
        int $limit = 0
    ): array;

    /**
     * @param array $options
     *
     * @return int
     */
    public function getAllRulesCount(array $options = array()): int;

    /**
     * Get all Rules that are affecting to the specific calendar_id
     *
     * @param int $calendar_id
     * @param array $options
     * @param string $orderby
     * @param string $order
     *
     * @return Rule[]
     */
    public function getAllRulesForCalendar(
        int $calendar_id,
        array $options = array(),
        string $orderby = "priority",
        string $order = "asc"
    ): array;

    /**
     * @param int $calendar_id
     * @param DateTime $dateTime
     * @param array $options
     * @param string $orderby
     * @param string $order
     *
     * @return Rule[]
     */
    public function getAllRulesForCalendarForDateTime(
        int $calendar_id,
        DateTime $dateTime,
        array $options = array(),
        string $orderby = "priority",
        string $order = "asc"
    ): array;

    /**
     * @param Rule $rule
     * @param DateTime $date_time
     */
    public function doesRuleWorksOnDateTime(Rule $rule, DateTime $date_time);
}