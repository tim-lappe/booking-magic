<?php

namespace TLBM\Rules\Actions\Merging\Contracts;

interface CapacityMergeResultInterface extends MergeResultInterface
{

    /**
     * @return int
     */
    public function getCapacityRemaining(): int;

    /**
     * @param int $capacity
     *
     * @return mixed
     */
    public function setCapacityRemaining(int $capacity);

    /**
     * @return int
     */
    public function getCapacityOriginal(): int;

    /**
     * @param int $capacityOriginal
     *
     * @return void
     */
    public function setCapacityOriginal(int $capacityOriginal): void;
}