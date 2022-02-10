<?php

namespace TLBM\Rules\Actions\Merging\Merger;

use TLBM\Rules\Actions\ActionData\ActionData;
use TLBM\Rules\Actions\Merging\Context\MergeContext;
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

    /**
     * @var ?MergeContext
     */
    protected ?MergeContext $mergeContext;

    public function __construct(ActionData $actionData, ?Merger $nextMerger = null)
    {
        $this->actionData = $actionData;
        $this->nextMerging = $nextMerger;
    }

    abstract public function merge(?MergeResultInterface $mergeResult = null): ?MergeResultInterface;

    /**
     * @return ?MergeContext
     */
    public function getMergeContext(): ?MergeContext
    {
        return $this->mergeContext;
    }

    /**
     * @param ?MergeContext $mergeContext
     */
    public function setMergeContext(?MergeContext $mergeContext): void
    {
        $this->mergeContext = $mergeContext;
    }

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

    /**
     * @param string $term
     * @param mixed $mergeResult1
     * @param mixed $mergeResult2
     *
     * @return mixed|null
     */
    public function sumUpResults(string $term, $mergeResult1, $mergeResult2)
    {
        return $mergeResult1;
    }
}