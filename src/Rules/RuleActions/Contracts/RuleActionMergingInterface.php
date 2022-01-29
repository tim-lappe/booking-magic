<?php

namespace TLBM\Rules\RuleActions\Contracts;

use TLBM\Rules\RuleActions\MergeEntities\Contracts\MergeEntityInterface;

interface RuleActionMergingInterface
{

    /**
     * @param MergeEntityInterface $mergeObj
     *
     * @return MergeEntityInterface
     */
    public function merge(MergeEntityInterface &$mergeObj): MergeEntityInterface;

    /**
     * @return MergeEntityInterface
     */
    public function getEmptyMergeInstance(): MergeEntityInterface;
}