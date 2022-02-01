<?php

namespace TLBM\Rules\Actions;

use JsonSerializable;
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
     * @param ExtendedDateTime $dateTime
     * @param mixed $mergedActions
     */
    public function __construct(ExtendedDateTime $dateTime, $mergedActions)
    {
        $this->mergedActions = $mergedActions;
        $this->dateTime      = $dateTime;
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