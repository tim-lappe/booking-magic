<?php

namespace TLBM\Repository\Query;


use JsonSerializable;
use TLBM\Utilities\ExtendedDateTime;

class TimeBasedQueryResult implements JsonSerializable
{
    /**
     * @var ?ExtendedDateTime
     */
    private ?ExtendedDateTime $dateTime;

    /**
     * @var mixed
     */
    private $queryResult;

    /**
     * @param ?ExtendedDateTime $dateTime
     * @param mixed $queryResult
     */
    public function __construct(?ExtendedDateTime $dateTime, $queryResult)
    {
        $this->dateTime    = $dateTime;
        $this->queryResult = $queryResult;
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
     * @return mixed
     */
    public function getQueryResult()
    {
        return $this->queryResult;
    }

    /**
     * @param mixed $queryResult
     */
    public function setQueryResult($queryResult): void
    {
        $this->queryResult = $queryResult;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "dateTime" => $this->dateTime,
            "queryResult" => $this->queryResult
        ];
    }
}