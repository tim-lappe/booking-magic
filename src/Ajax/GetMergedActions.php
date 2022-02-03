<?php

namespace TLBM\Ajax;

use TLBM\Ajax\Contracts\AjaxFunctionInterface;
use TLBM\Booking\Contracts\CalendarBookingManagerInterface;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\MainFactory;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;
use TLBM\Rules\Contracts\RulesQueryInterface;
use TLBM\Rules\Actions\ActionsMerging;
use TLBM\Utilities\ExtendedDateTime;

class GetMergedActions implements AjaxFunctionInterface
{

    /**
     * @var RuleActionsManagerInterface
     */
    private RuleActionsManagerInterface $ruleActionsManager;

    /**
     * @var CalendarBookingManagerInterface
     */
    private CalendarBookingManagerInterface $calendarBookingManager;

    /**
     * @var CalendarManagerInterface
     */
    private CalendarManagerInterface $calendarManager;

    public function __construct
    (
        CalendarManagerInterface $calendarManager,
        CalendarBookingManagerInterface $calendarBookingManager,
        RuleActionsManagerInterface $ruleActionsManager
    )
    {
        $this->calendarBookingManager = $calendarBookingManager;
        $this->ruleActionsManager = $ruleActionsManager;
        $this->calendarManager = $calendarManager;
    }

    /**
     * @return string
     */
    public function getFunctionName(): string
    {
        return "getBookingOptions";
    }

    /**
     * @param mixed $assocData
     *
     * @return array
     */
    public function execute($assocData): array
    {
        /**
         * @var RulesQueryInterface $query
         */
        $query = MainFactory::create(RulesQueryInterface::class);
        $calendar = null;
        if(isset($assocData['options']['dataSourceId']) && isset($assocData['options']['dataSourceType'])) {
            if($assocData['options']['dataSourceType'] == "calendar") {
                $query->setTypeCalendar($assocData['options']['dataSourceId']);
                $calendar = $this->calendarManager->getCalendar($assocData['options']['dataSourceId']);
            }
        }

        $fromDateTime = new ExtendedDateTime();
        $fromDateTime->setFromObject($assocData['fromDateTime']);
        $toDateTime = new ExtendedDateTime();
        $toDateTime->setFromObject($assocData['toDateTime']);

        $focusedDateTime = new ExtendedDateTime();
        if(isset($assocData['options']['focusedDateTime'])) {
            $focusedDateTime->setFromObject($assocData['options']['focusedDateTime']);
        } else {
            $focusedDateTime->setInvalid(true);
        }

        if ( !$fromDateTime->isInvalid() && !$toDateTime->isInvalid()) {
            $query->setDateTimeRange($fromDateTime, $toDateTime);

        } elseif (!$focusedDateTime->isInvalid()) {
            $query->setDateTime($focusedDateTime);

        } else {
            return array(
                "error" => true
            );
        }

        $actionsReader = new ActionsMerging($this->ruleActionsManager, $query);

        $mergedCollection = $actionsReader->getRuleActionsMerged();
        foreach ($mergedCollection as $mergeData) {
            $actions = $mergeData->getMergedActions();
            if(isset($actions['dateCapacity'])) {
                $actions['dateCapacity'] = $this->calendarBookingManager->getFreeCapacitiesForCalendar($calendar, $mergeData->getDateTime());
            }
        }

        return array(
            "actionsResult" => $mergedCollection
        );
    }
}