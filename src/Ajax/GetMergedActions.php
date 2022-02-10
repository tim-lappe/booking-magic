<?php

namespace TLBM\Ajax;

use TLBM\Ajax\Contracts\AjaxFunctionInterface;
use TLBM\Booking\Contracts\CalendarBookingManagerInterface;
use TLBM\Entity\Calendar;
use TLBM\Entity\CalendarGroup;
use TLBM\MainFactory;
use TLBM\Output\Calendar\CalendarDisplay;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Repository\Query\CalendarGroupQuery;
use TLBM\Repository\Query\CalendarQuery;
use TLBM\Repository\Query\Contracts\FullRuleActionQueryInterface;
use TLBM\Repository\RepositoryLogger;
use TLBM\Rules\Actions\ActionsMerging;
use TLBM\Utilities\ExtendedDateTime;

class GetMergedActions implements AjaxFunctionInterface
{

    /**
     * @var CalendarBookingManagerInterface
     */
    private CalendarBookingManagerInterface $calendarBookingManager;

    /**
     * @var EntityRepositoryInterface $entityRepository;
     */
    private EntityRepositoryInterface $entityRepository;

    public function __construct
    (
        EntityRepositoryInterface $entityRepository,
        CalendarBookingManagerInterface $calendarBookingManager
    )
    {
        $this->calendarBookingManager = $calendarBookingManager;
        $this->entityRepository = $entityRepository;
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
        $display = MainFactory::create(CalendarDisplay::class);
        $display->assignFromAssoc($assocData['display']);

        $logger = MainFactory::create(RepositoryLogger::class);
        $logger->start();

        $actionsMerging = MainFactory::create(ActionsMerging::class);
        $calendars = [];

        if(count($display->getCalendarIds()) > 0) {
            foreach ($display->getCalendarIds() as $id) {
                $calendars[] = $this->entityRepository->getEntity(Calendar::class, $id);
            }
        }

        if(count($display->getGroupIds()) > 0) {
            $groupQuery = MainFactory::create(CalendarGroupQuery::class);
            $groupQuery->setGroupIds($display->getGroupIds());

            /**
             * @var CalendarGroup $group
             */
            foreach ($groupQuery->getResult() as $group) {
                $calendarQuery = MainFactory::create(CalendarQuery::class);
                $calendarQuery->setCalendarSelection($group->getCalendarSelection());
                $calendars = array_merge($calendars, iterator_to_array($calendarQuery->getResult()));
            }
        }

        $fromDateTime = new ExtendedDateTime();
        $fromDateTime->setFromObject($assocData['fromDateTime']);
        $toDateTime = new ExtendedDateTime();
        $toDateTime->setFromObject($assocData['toDateTime']);
        $focusedDateTime = new ExtendedDateTime();

        if ( !$fromDateTime->isInvalid() && !$toDateTime->isInvalid()) {
            $actionsMerging->setDateTimeRange($fromDateTime, $toDateTime);

        } elseif (!$focusedDateTime->isInvalid()) {
            $actionsMerging->setDateTime($focusedDateTime);

        } else {
            return array(
                "error" => true
            );
        }

        $calendarIds = [];
        array_walk($calendars, function ($calendar) use (&$calendarIds) {
            $calendarIds[] = $calendar->getId();
        });

        $editedCollection = [];

        $actionsMerging->setCalendarIds($calendarIds);
        foreach ($actionsMerging->getRuleActionsMerged() as $mergeData) {
            $actions = $mergeData->getMergedActions();
            $booked = $this->calendarBookingManager->getBookedSlots($calendarIds, $mergeData->getDateTime());
            $actions['dateCapacity'] -= $booked;
         //   var_dump("Bookings: " . $booked . " Time: " . $mergeData->getDateTime()->format() . " Cals: " . implode(",", $calendarIds));

            $mergeData->setMergedActions($actions);
            $editedCollection[] = $mergeData;
        }

        $queries = $logger->end();

       // echo count($queries);

        return array(
            "actionsResult" => $editedCollection
        );
    }
}