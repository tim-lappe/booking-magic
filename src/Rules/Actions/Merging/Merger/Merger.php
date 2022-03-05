<?php

namespace TLBM\Rules\Actions\Merging\Merger;

use TLBM\Rules\Actions\ActionData\ActionData;
use TLBM\Rules\Actions\Merging\Context\MergeContext;
use TLBM\Rules\Actions\Merging\Contracts\MergeResultInterface;
use TLBM\Utilities\ExtendedDateTime;

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

    /**
     * @var ExtendedDateTime
     */
    protected ExtendedDateTime $dateTimeContext;

    public function __construct(ActionData $ruleActionData, ?Merger $nextMerger = null)
    {
        $this->actionData = $ruleActionData;
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
     * @return ExtendedDateTime
     */
    public function getDateTimeContext(): ExtendedDateTime
    {
        return $this->dateTimeContext;
    }

    /**
     * @param ExtendedDateTime $dateTimeContext
     */
    public function setDateTimeContext(ExtendedDateTime $dateTimeContext): void
    {
        $this->dateTimeContext = $dateTimeContext;
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
     * @param array $calendarIds
     * @param MergeResultInterface $mergeResult
     *
     * @return mixed
     */
    public function lastStepModification(string $term, array $calendarIds, MergeResultInterface $mergeResult)
    {
        return $mergeResult;
    }
}