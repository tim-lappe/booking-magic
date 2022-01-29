<?php

namespace TLBM\Rules\RuleActions\MergeEntities\Contracts;

interface MergeEntityInterface
{
    /**
     * @return string
     */
    public function getMergeTerm(): string;
}