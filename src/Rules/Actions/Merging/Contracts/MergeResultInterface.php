<?php

namespace TLBM\Rules\Actions\Merging\Contracts;

interface MergeResultInterface
{
    /**
     * @param MergeResultInterface ...$mergeResults
     *
     */
    public function sumResults(MergeResultInterface... $mergeResults);
}