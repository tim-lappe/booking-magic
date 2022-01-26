<?php

namespace TLBM\Rules\RuleActions\MergeEntities;

interface CapacityMerge
{

    public function getCapacity(): int;

    public function setCapacity(int $capacity);
}