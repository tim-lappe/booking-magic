<?php

namespace TLBM\Rules\Actions\Merging\Results;

use TLBM\Rules\Actions\Merging\Contracts\CapacityMergeResultInterface;
use TLBM\Rules\Actions\Merging\Contracts\MergeResultInterface;

class CapacityResult implements MergeResultInterface, CapacityMergeResultInterface
{

    /**
     * @var int
     */
    public int $capacityRemaining = 0;

    /**
     * @var int
     */
    public int $capacityOriginal = 0;

    /**
     * @return int
     */
    public function getCapacityRemaining(): int
    {
        return $this->capacityRemaining;
    }

    /**
     * @param int $capacityRemaining
     */
    public function setCapacityRemaining(int $capacityRemaining): void
    {
        $this->capacityRemaining = max(0, $capacityRemaining);
    }

    /**
     * @return array
     */
    public function getMergeResult(): array
    {
        return [
            "remaining" => $this->getCapacityRemaining(),
            "original" => $this->getCapacityOriginal()
        ];
    }

    /**
     * @return int
     */
    public function getCapacityOriginal(): int
    {
        return $this->capacityOriginal;
    }

    /**
     * @param int $capacityOriginal
     */
    public function setCapacityOriginal(int $capacityOriginal): void
    {
        $this->capacityOriginal = $capacityOriginal;
    }

    /**
     * @param MergeResultInterface ...$mergeResults
     *
     */
    public function sumResults(MergeResultInterface ...$mergeResults)
    {
        foreach ($mergeResults as $result) {
            if($result instanceof CapacityResult) {
                $this->setCapacityOriginal($this->getCapacityOriginal() + $result->getCapacityOriginal());
                $this->setCapacityRemaining($this->getCapacityRemaining() + $result->getCapacityRemaining());
            }
        }
    }
}