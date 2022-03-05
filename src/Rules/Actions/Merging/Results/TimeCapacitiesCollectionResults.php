<?php

namespace TLBM\Rules\Actions\Merging\Results;

use TLBM\Rules\Actions\Merging\Contracts\MergeResultInterface;

class TimeCapacitiesCollectionResults implements MergeResultInterface
{
    /**
     * @var TimedCapacityResult[]
     */
    public array $timeSlotsCapacities = [];

    /**
     * @return TimedCapacityResult[]
     */
    public function getTimeCapacities(): array
    {
        return $this->timeSlotsCapacities;
    }

    /**
     * @param int $hour
     * @param int $minute
     *
     * @return TimedCapacityResult|null
     */
    public function getTimeCapacityAt(int $hour, int $minute): ?TimedCapacityResult
    {
        $results = array_filter($this->timeSlotsCapacities, function (TimedCapacityResult $timedCapacityResult) use ($minute, $hour) {
            return $timedCapacityResult->getMinute() == $minute && $timedCapacityResult->getHour() == $hour;
        });

        if(count($results) > 0) {
            return array_values($results)[0];
        }

        return null;
    }

    /**
     * @param TimedCapacityResult[] $timeCapacities
     */
    public function setTimeCapacities(array $timeCapacities): void
    {
        $this->timeSlotsCapacities = $timeCapacities;
    }

    public function addTimeCapacity(TimedCapacityResult $timedCapacityResult)
    {
        $this->timeSlotsCapacities[] = $timedCapacityResult;
        usort($this->timeSlotsCapacities, function (TimedCapacityResult $a, TimedCapacityResult $b) {
            if($a->getHour() > $b->getHour()) {
                return 1;
            } elseif ($a->getHour() < $b->getHour()) {
                return -1;
            } elseif ($a->getMinute() > $b->getMinute()) {
                return 1;
            } elseif ($a->getMinute() < $b->getMinute()) {
                return -1;
            }

            return 0;
        });
    }

    /**
     * @param MergeResultInterface ...$mergeResults
     *
     */
    public function sumResults(MergeResultInterface ...$mergeResults)
    {
        foreach ($mergeResults as $result) {
            if($result instanceof TimeCapacitiesCollectionResults) {
                foreach ($result->getTimeCapacities() as $timeCapacityToSum) {
                    $existingTimeCap = $this->getTimeCapacityAt($timeCapacityToSum->getHour(), $timeCapacityToSum->getMinute());
                    if($existingTimeCap != null) {
                        $existingTimeCap->sumResults($timeCapacityToSum);
                    } else {
                        $this->addTimeCapacity($timeCapacityToSum);
                    }
                }
            }
        }
    }
}