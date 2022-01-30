<?php

namespace TLBM\Rules\Actions\Merging\Contracts;

interface CapacityMergeResultInterface extends MergeResultInterface
{

    /**
     * @return int
     */
    public function getCapacity(): int;

    /**
     * @param int $capacity
     *
     * @return mixed
     */
    public function setCapacity(int $capacity);
}