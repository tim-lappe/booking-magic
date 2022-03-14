<?php

namespace TLBM\Rules\Actions\Merging\Merger;

use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\LatestBookingPossibility;
use TLBM\Booking\Contracts\CalendarBookingManagerInterface;
use TLBM\Entity\RuleAction;
use TLBM\MainFactory;
use TLBM\Rules\Actions\ActionData\CapacityActionData;
use TLBM\Rules\Actions\Merging\CapacityMergeHelper;
use TLBM\Rules\Actions\Merging\Contracts\MergeResultInterface;
use TLBM\Rules\Actions\Merging\Results\TimeCapacitiesCollectionResults;
use TLBM\Rules\Actions\Merging\Results\TimedCapacityResult;
use TLBM\Utilities\ExtendedDateTime;

class TimeCapacityMerger extends Merger
{

    /**
     * @var RuleAction
     */
    private RuleAction $ruleAction;

    public function __construct(RuleAction $ruleAction, ?Merger $nextMerger = null)
    {
        parent::__construct(new CapacityActionData($ruleAction->getData()), $nextMerger);
        $this->ruleAction = $ruleAction;
    }

    /**
     * @param MergeResultInterface|null $mergeResult
     *
     * @return MergeResultInterface|null
     */
    public function merge(?MergeResultInterface $mergeResult = null): ?MergeResultInterface
    {
        if($mergeResult == null) {
            $mergeResult = MainFactory::create(TimeCapacitiesCollectionResults::class);
        }

        if($mergeResult instanceof TimeCapacitiesCollectionResults) {
            $hour   = $this->getRuleAction()->getTimeHour();
            $minute = $this->getRuleAction()->getTimeMin();

            $timedCap = $mergeResult->getTimeCapacityAt($hour, $minute);

            if($timedCap == null) {
                $timedCapResult = new TimedCapacityResult();
                $timedCapResult->setHour($hour);
                $timedCapResult->setMinute($minute);
                $timedCap = $timedCapResult;
                $mergeResult->addTimeCapacity($timedCap);
            }

            $capacityMergeHelper = new CapacityMergeHelper($timedCap);
            $capacityMergeHelper->mergeWithAction($this->getActionData());

            if ($this->getNextMerging()) {
                return $this->getNextMerging()->merge($mergeResult);
            }

            return $mergeResult;
        }

        return MainFactory::create(TimeCapacitiesCollectionResults::class);
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
        if ($term == "timeCapacities" && $mergeResultSummedUp instanceof TimeCapacitiesCollectionResults && $mergeResultToAdd instanceof TimeCapacitiesCollectionResults) {
            foreach ($mergeResultSummedUp->getTimeCapacities() as $timedCapacityResultSummedUp) {
                foreach ($mergeResultToAdd->getTimeCapacities() as $timedCapacityToAdd) {
                    if ($timedCapacityResultSummedUp->getHour() == $timedCapacityToAdd->getHour() && $timedCapacityResultSummedUp->getMinute() == $timedCapacityToAdd->getMinute()) {
                        $timedCapacityResultSummedUp->setCapacityOriginal($timedCapacityResultSummedUp->getCapacityOriginal() + $timedCapacityToAdd->getCapacityOriginal());
                        $timedCapacityResultSummedUp->setCapacityRemaining($timedCapacityResultSummedUp->getCapacityRemaining() + $timedCapacityToAdd->getCapacityRemaining());
                    }
                }
            }
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
        if ($term == "timeCapacities" && $mergeResult instanceof TimeCapacitiesCollectionResults) {
            $settingsManager          = MainFactory::create(SettingsManagerInterface::class);
            $latestBookingPossibility = $settingsManager->getSetting(LatestBookingPossibility::class);

            /**
             * @var ExtendedDateTime $latestDt
             */
            $latestDt = $latestBookingPossibility->getLatestPossibilityDateTime();

            foreach ($mergeResult->getTimeCapacities() as $timeCapacity) {
                $calendarBookingManager = MainFactory::get(CalendarBookingManagerInterface::class);

                $dateTime = $this->getDateTimeContext()->copy();
                $dateTime->setFullDay(false);
                $dateTime->setHour($timeCapacity->getHour());
                $dateTime->setMinute($timeCapacity->getMinute());
                $dateTime->setSeconds(0);

                if ( !$dateTime->isEarlierThan($latestDt)) {
                    $booked = $calendarBookingManager->getBookedSlots($calendarIds, $dateTime);
                    $timeCapacity->setCapacityRemaining(max(0, $timeCapacity->getCapacityOriginal() - $booked));
                }
            }
        }

        return parent::lastStepModification($term, $calendarIds, $mergeResult); // TODO: Change the autogenerated stub
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
     * @return RuleAction
     */
    public function getRuleAction(): RuleAction
    {
        return $this->ruleAction;
    }

    /**
     * @param RuleAction $ruleAction
     */
    public function setRuleAction(RuleAction $ruleAction): void
    {
        $this->ruleAction = $ruleAction;
    }
}