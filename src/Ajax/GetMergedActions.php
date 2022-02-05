<?php

namespace TLBM\Ajax;

use TLBM\Ajax\Contracts\AjaxFunctionInterface;
use TLBM\Booking\Contracts\CalendarBookingManagerInterface;
use TLBM\Calendar\Contracts\CalendarRepositoryInterface;
use TLBM\MainFactory;
use TLBM\Repository\Query\Contracts\FullRuleActionQueryInterface;
use TLBM\Rules\Actions\ActionsMerging;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;
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
     * @var CalendarRepositoryInterface
     */
    private CalendarRepositoryInterface $calendarManager;

    public function __construct
    (
        CalendarRepositoryInterface $calendarManager,
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
         * @var FullRuleActionQueryInterface $query
         */
        $query = MainFactory::create(FullRuleActionQueryInterface::class);
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
        $editedCollection = [];
        foreach ($mergedCollection as $mergeData) {
            $actions = $mergeData->getMergedActions();

            //TODO: Get Remaining Slots muss auch für calendar=null funktionieren und dann für alle Kalendar die Kapazitäten zurückgeben
            $actions['dateCapacity'] = $this->calendarBookingManager->getRemainingSlots($calendar, $mergeData->getDateTime());

            $mergeData->setMergedActions($actions);
            $editedCollection[] = $mergeData;
        }

        return array(
            "actionsResult" => $editedCollection
        );
    }
}