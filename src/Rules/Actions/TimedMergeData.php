<?php

namespace TLBM\Rules\Actions;

use JsonSerializable;
use TLBM\Rules\Actions\Merging\Contracts\MergeResultInterface;
use TLBM\Rules\Actions\Merging\Merger\Merger;
use TLBM\Utilities\ExtendedDateTime;

class TimedMergeData implements JsonSerializable
{

    /**
     * @var MergeResultInterface[]
     */
    private array $mergedActions;

    /**
     * @var ExtendedDateTime
     */
    private ExtendedDateTime $dateTime;

    /**
     * @var ?Merger[]
     */
    private ?array $usedMergers;

    /**
     * @param ExtendedDateTime $dateTime
     * @param MergeResultInterface[] $mergedActions
     * @param Merger[]|null $usedMergers
     */
    public function __construct(ExtendedDateTime $dateTime, array $mergedActions, ?array $usedMergers = null)
    {
        $this->mergedActions = $mergedActions;
        $this->dateTime      = $dateTime;
        $this->usedMergers = $usedMergers;
    }

    /**
     * @return MergeResultInterface[]
     */
    public function getMergeResult(): array
    {
        return $this->mergedActions;
    }

    /**
     * @param MergeResultInterface[] $mergedActions
     */
    public function setMergeResult(array $mergedActions): void
    {
        $this->mergedActions = $mergedActions;
    }

    /**
     * @param string $term
     * @param MergeResultInterface $value
     *
     * @return void
     */
    public function setSingleMergeResult(string $term, MergeResultInterface $value)
    {
        $this->mergedActions[$term] = $value;
    }

    /**
     * @return ExtendedDateTime
     */
    public function getDateTime(): ExtendedDateTime
    {
        return $this->dateTime;
    }

    /**
     * @param ExtendedDateTime $dateTime
     */
    public function setDateTime(ExtendedDateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return Merger[]|null
     */
    public function getUsedMergers(): ?array
    {
        return $this->usedMergers;
    }

    /**
     * @param string $term
     *
     * @return ?Merger
     */
    public function getSingleMerger(string $term): ?Merger
    {
        return $this->usedMergers[$term] ?? null;
    }

    /**
     * @param Merger[]|null $usedMergers
     */
    public function setUsedMergers(?array $usedMergers): void
    {
        $this->usedMergers = $usedMergers;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "dateTime" => $this->dateTime,
            "mergedActions" => $this->mergedActions
        ];
    }
}