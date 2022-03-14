<?php

namespace TLBM\Rules\Actions\Merging\Merger;

use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\LatestBookingPossibility;
use TLBM\Booking\Contracts\CalendarBookingManagerInterface;
use TLBM\MainFactory;
use TLBM\Rules\Actions\ActionData\CapacityActionData;
use TLBM\Rules\Actions\Merging\CapacityMergeHelper;
use TLBM\Rules\Actions\Merging\Contracts\MergeResultInterface;
use TLBM\Rules\Actions\Merging\Results\CapacityResult;
use TLBM\Utilities\ExtendedDateTime;

class DateCapacityMerger extends Merger
{
    /**
     * @param MergeResultInterface|null $mergeResult
     *
     * @return CapacityResult|null
     */
    public function merge(?MergeResultInterface $mergeResult = null): ?CapacityResult
    {
        if($mergeResult == null) {
            $mergeResult = MainFactory::create(CapacityResult::class);
        }

        if($mergeResult instanceof CapacityResult) {
            $capacityMergeHelper = new CapacityMergeHelper($mergeResult);
            $capacityMergeHelper->mergeWithAction($this->getActionData());

            if ($this->getNextMerging()) {
                return $this->getNextMerging()->merge($mergeResult);
            }

            return $mergeResult;
        }

        return MainFactory::create(CapacityResult::class);
    }

    /**
     * @return CapacityActionData|null
     */
    protected function getActionData(): ?CapacityActionData
    {
        $actionData = parent::getActionData();
        if($actionData instanceof CapacityActionData) {
            return $actionData;
        }

        return null;
    }

    /**
     * @return DateCapacityMerger|null
     */
    protected function getNextMerging(): ?DateCapacityMerger
    {
        $next = parent::getNextMerging();
        if($next instanceof DateCapacityMerger) {
            return $next;
        }

        return null;
    }

    /**
     * @param string $term
     * @param array $calendarIds
     * @param MergeResultInterface $mergeResultSummedUp
     * @param MergeResultInterface $mergeResultToAdd
     *
     * @return MergeResultInterface
     */
    public function sumUpResults(string $term, array $calendarIds, MergeResultInterface $mergeResultSummedUp, MergeResultInterface $mergeResultToAdd): MergeResultInterface
    {
        if ($term == "dateCapacity" && $mergeResultSummedUp instanceof CapacityResult && $mergeResultToAdd instanceof CapacityResult) {
            $mergeResultSummedUp->setCapacityOriginal($mergeResultSummedUp->getCapacityOriginal() + $mergeResultToAdd->getCapacityOriginal());
            $mergeResultSummedUp->setCapacityRemaining($mergeResultSummedUp->getCapacityRemaining() + $mergeResultToAdd->getCapacityRemaining());
        }

        return $mergeResultSummedUp;
    }

    /**
     * @param string $term
     * @param array $calendarIds
     * @param MergeResultInterface $mergeResult
     *
     * @return MergeResultInterface
     */
    public function lastStepModification(string $term, array $calendarIds, MergeResultInterface $mergeResult): MergeResultInterface
    {
        if($mergeResult instanceof CapacityResult) {
            $settingsManager        = MainFactory::get(SettingsManagerInterface::class);
            $calendarBookingManager = MainFactory::get(CalendarBookingManagerInterface::class);

            $latestBookingPossibility = $settingsManager->getSetting(LatestBookingPossibility::class);

            /**
             * @var ExtendedDateTime $latestDt
             */
            $latestDt = $latestBookingPossibility->getLatestPossibilityDateTime();
            $dateTime = $this->getDateTimeContext()->copy();
            $dateTime->setFullDay(true);

            if ( !$dateTime->isEarlierThan($latestDt)) {
                $booked = $calendarBookingManager->getBookedSlots($calendarIds, $dateTime);
                $mergeResult->setCapacityRemaining(max(0, $mergeResult->getCapacityOriginal() - $booked));

                return $mergeResult;
            } else {
                $mergeResult->setCapacityRemaining(0);
            }
        }

        return parent::lastStepModification($term, $calendarIds, $mergeResult);
    }
}