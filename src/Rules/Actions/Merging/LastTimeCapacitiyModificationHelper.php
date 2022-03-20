<?php

namespace TLBM\Rules\Actions\Merging;

use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\LatestBookingPossibility;
use TLBM\Booking\Contracts\CalendarBookingManagerInterface;
use TLBM\MainFactory;
use TLBM\Repository\CacheManager;
use TLBM\Rules\Actions\Merging\Results\TimeCapacitiesCollectionResults;
use TLBM\Utilities\ExtendedDateTime;

class LastTimeCapacitiyModificationHelper
{
    /**
     * @var TimeCapacitiesCollectionResults
     */
    private TimeCapacitiesCollectionResults $mergeResult;

    /**
     * @var array
     */
    private array $calendarIds;

    /**
     * @var ExtendedDateTime
     */
    private ExtendedDateTime $dateTimeContext;

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    /**
     * @var CalendarBookingManagerInterface
     */
    private CalendarBookingManagerInterface $calendarBookingManager;

    /**
     * @param SettingsManagerInterface $settingsManager
     * @param CalendarBookingManagerInterface $calendarBookingManager
     */
    public function __construct(SettingsManagerInterface $settingsManager, CalendarBookingManagerInterface $calendarBookingManager)
    {
        $this->settingsManager        = $settingsManager;
        $this->calendarBookingManager = $calendarBookingManager;
    }

    public function modify(): TimeCapacitiesCollectionResults
    {
        $cacheManager = MainFactory::get(CacheManager::class);
        if ($cacheManager->entryExists($this->getHashObj())) {
            $data = $cacheManager->getData($this->getHashObj());
            if ($data instanceof TimeCapacitiesCollectionResults) {
                return $data;
            }
        }

        $latestBookingPossibility = $this->settingsManager->getSetting(LatestBookingPossibility::class);

        /**
         * @var ExtendedDateTime $latestDt
         */
        $latestDt = $latestBookingPossibility->getLatestPossibilityDateTime();

        foreach ($this->mergeResult->getTimeCapacities() as $timeCapacity) {
            $dateTime = $this->dateTimeContext->copy();
            $dateTime->setFullDay(false);
            $dateTime->setHour($timeCapacity->getHour());
            $dateTime->setMinute($timeCapacity->getMinute());
            $dateTime->setSeconds(0);

            if ( !$dateTime->isEarlierThan($latestDt)) {
                $booked = $this->calendarBookingManager->getBookedSlots($this->calendarIds, $dateTime);
                $timeCapacity->setCapacityRemaining(max(0, $timeCapacity->getCapacityOriginal() - $booked));
            }
        }

        $cacheManager->setData($this->getHashObj(), $this->mergeResult);

        return $this->mergeResult;
    }

    /**
     * @return array
     */
    public function getHashObj(): array
    {
        return ["name" => "lastTimeCapacitiyModificationHelper",
            "dateTime" => $this->getDateTimeContext(),
            "calendarIds" => $this->getCalendarIds()
        ];
    }

    /**
     * @return ExtendedDateTime
     */
    public function getDateTimeContext(): ExtendedDateTime
    {
        return $this->dateTimeContext;
    }

    /**
     * @param ExtendedDateTime $dateTimeContext
     */
    public function setDateTimeContext(ExtendedDateTime $dateTimeContext): void
    {
        $this->dateTimeContext = $dateTimeContext;
    }

    /**
     * @return array
     */
    public function getCalendarIds(): array
    {
        return $this->calendarIds;
    }

    /**
     * @param array $calendarIds
     */
    public function setCalendarIds(array $calendarIds): void
    {
        $this->calendarIds = $calendarIds;
    }

    /**
     * @return TimeCapacitiesCollectionResults
     */
    public function getMergeResult(): TimeCapacitiesCollectionResults
    {
        return $this->mergeResult;
    }

    /**
     * @param TimeCapacitiesCollectionResults $mergeResult
     */
    public function setMergeResult(TimeCapacitiesCollectionResults $mergeResult): void
    {
        $this->mergeResult = $mergeResult;
    }
}