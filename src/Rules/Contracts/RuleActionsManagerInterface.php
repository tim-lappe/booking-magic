<?php

namespace TLBM\Rules\Contracts;

use DateTime;
use TLBM\Entity\Calendar;
use TLBM\Entity\RuleAction;
use TLBM\Rules\RuleActions\RuleActionMergingBase;

interface RuleActionsManagerInterface
{

    /**
     * @param string $term
     * @param $class
     *
     * @return mixed
     */
    public function registerActionMerger(string $term, $class);

    /**
     *
     * @param RuleAction $action
     *
     * @return ?RuleActionMergingBase
     */
    public function getActionMerger(RuleAction $action): ?RuleActionMergingBase;

    /**
     * @param Calendar $calendar
     * @param DateTime $date_time
     *
     * @return RuleAction[]
     */
    public function getActionsForDateTime(Calendar $calendar, DateTime $date_time): array;
}