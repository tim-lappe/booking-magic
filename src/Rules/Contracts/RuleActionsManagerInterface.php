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
     * @param string $class
     *
     * @return bool
     */
    public function registerActionMerger(string $term, string $class): bool;

    /**
     * @return array
     */
    public function getAllActionsMerger(): array;


    /**
     *
     * @param RuleAction $action
     *
     * @return ?RuleActionMergingBase
     */
    public function getActionMerger(RuleAction $action): ?RuleActionMergingBase;

    /**
     * @param Calendar $calendar
     * @param DateTime $dateTime
     *
     * @return RuleAction[]
     */
    public function getActionsForDateTime(Calendar $calendar, DateTime $dateTime): array;
}