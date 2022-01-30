<?php

namespace TLBM\Rules\Actions\Merging\Merger;

use TLBM\Rules\Actions\ActionData\ActionData;
use TLBM\Rules\Actions\Merging\Contracts\MergeResultInterface;

abstract class Merger
{
    /**
     * @var ActionData
     */
    private ActionData $actionData;

    /**
     * @var Merger|null
     */
    private ?Merger $nextMerging;

    public function __construct(ActionData $actionData, ?Merger $nextMerger = null)
    {
        $this->actionData = $actionData;
        $this->nextMerging = $nextMerger;
    }

    abstract public function merge(?MergeResultInterface $mergeResult = null): ?MergeResultInterface;

    /**
     * @return ?ActionData
     */
    protected function getActionData(): ?ActionData
    {
        return $this->actionData;
    }

    /**
     * @return ?Merger
     */
    protected function getNextMerging(): ?Merger
    {
        return $this->nextMerging;
    }
}