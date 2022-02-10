<?php

namespace TLBM\Rules\Actions;

use JsonSerializable;
use TLBM\Rules\Actions\Merging\Merger\Merger;
use TLBM\Utilities\ExtendedDateTime;

class TimedMergeData implements JsonSerializable
{

    /**
     * @var mixed
     */
    private $mergedActions;

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
     * @param mixed $mergedActions
     * @param Merger|null $usedMergers
     */
    public function __construct(ExtendedDateTime $dateTime, $mergedActions, ?array $usedMergers = null)
    {
        $this->mergedActions = $mergedActions;
        $this->dateTime      = $dateTime;
        $this->usedMergers = $usedMergers;
    }

    /**
     * @return mixed
     */
    public function getMergedActions()
    {
        return $this->mergedActions;
    }

    /**
     * @param mixed $mergedActions
     */
    public function setMergedActions($mergedActions): void
    {
        $this->mergedActions = $mergedActions;
    }

    /**
     * @param string $term
     * @param mixed $value
     *
     * @return void
     */
    public function setSingleMergeAction(string $term, $value)
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