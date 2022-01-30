<?php

namespace TLBM\Rules\Actions\Contracts;

use TLBM\Rules\Actions\Actions\LegacyMerging\Contracts\MergeEntityInterface;

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