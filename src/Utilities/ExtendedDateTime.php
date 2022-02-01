<?php

namespace TLBM\Utilities;

use DateInterval;
use DatePeriod;
use DateTime;
use Iterator;
use JsonSerializable;

const EXTDATETIME_INTERVAL_DAY = 0;

class ExtendedDateTime implements JsonSerializable
{

    /**
     * @var bool
     */
    protected bool $invalid = false;

    /**
     * @var bool
     */
    protected bool $fullDay = false;

    /**
     * @var DateTime
     */
    private DateTime $internalDateTime;

    public function __construct(?int $timestamp = null)
    {
        $this->internalDateTime = new DateTime();
        if($timestamp != null) {
            $this->internalDateTime->setTimestamp($timestamp);
        }

        $this->internalDateTime->setTimezone(wp_timezone());
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->internalDateTime->getTimestamp();
    }

    /**
     * @return int
     */
    public function getTimestampBeginOfDay(): int
    {
        $extDateTime = new ExtendedDateTime($this->getTimestamp());
        $extDateTime->setFullTime(0, 0, 1);
        return $extDateTime->getTimestamp();
    }

    /**
     * @return int
     */
    public function getTimestampEndOfDay(): int
    {
        $extDateTime = new ExtendedDateTime($this->getTimestamp());
        $extDateTime->setFullTime(23, 59, 59);
        return $extDateTime->getTimestamp();
    }

    /**
     * @return int
     */
    public function getWeekday(): int
    {
        return intval($this->internalDateTime->format("N"));
    }

    /**
     * @param ExtendedDateTime $dateTime
     *
     * @return bool
     */
    public function isSameDate(ExtendedDateTime $dateTime): bool
    {
        $sameDay =
            $this->getYear() == $dateTime->getYear() &&
            $this->getMonth() == $dateTime->getMonth() &&
            $this->getDay() == $dateTime->getDay();

        if($this->isFullDay()) {
            return $sameDay;
        }

        return
            $this->getHour() == $dateTime->getHour() &&
            $this->getMinute() == $dateTime->getMinute() &&
            $this->getSeconds() == $dateTime->getSeconds() &&
            $sameDay;
    }

    /**
     * @param int $hour
     * @param int $minute
     * @param int $seconds
     *
     * @return void
     */
    public function setFullTime(int $hour, int $minute, int $seconds)
    {
        $this->setHour($hour);
        $this->setMinute($minute);
        $this->setSeconds($seconds);
    }

    /**
     * @param int $timestamp
     *
     * @return void
     */
    public function setTimestamp(int $timestamp)
    {
        $this->internalDateTime->setTimestamp($timestamp);
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return intval($this->internalDateTime->format("Y"));
    }

    /**
     * @param int $year
     */
    public function setYear(int $year): void
    {
        $this->internalDateTime->setDate($year, $this->getMonth(), $this->getDay());
    }

    /**
     * @return int
     */
    public function getMonth(): int
    {
        return intval($this->internalDateTime->format("m"));
    }

    /**
     * @param int $month
     */
    public function setMonth(int $month): void
    {
        $this->internalDateTime->setDate($this->getYear(), $month, $this->getDay());
    }

    /**
     * @return int
     */
    public function getDay(): int
    {
        return intval($this->internalDateTime->format("d"));
    }

    /**
     * @param int $day
     */
    public function setDay(int $day): void
    {
        $this->internalDateTime->setDate($this->getYear(), $this->getMonth(), $day);
    }

    /**
     * @return int|null
     */
    public function getHour(): ?int
    {
        if($this->isFullDay()) {
            return null;
        }

        return intval($this->internalDateTime->format("H"));
    }

    /**
     * @param int|null $hour
     */
    public function setHour(?int $hour): void
    {
        $this->internalDateTime->setTime($hour, $this->getMinute(), $this->getDay());
    }

    /**
     * @return int|null
     */
    public function getMinute(): ?int
    {
        if($this->isFullDay()) {
            return null;
        }

        return intval($this->internalDateTime->format("i"));
    }

    /**
     * @param int|null $minute
     */
    public function setMinute(?int $minute): void
    {
        $this->internalDateTime->setTime($this->getHour(), $minute, $this->getDay());
    }

    /**
     * @return int|null
     */
    public function getSeconds(): ?int
    {
        if($this->isFullDay()) {
            return null;
        }

        return intval($this->internalDateTime->format("s"));
    }

    /**
     * @param int|null $seconds
     */
    public function setSeconds(?int $seconds): void
    {
        $this->internalDateTime->setTime($this->getHour(), $this->getMinute(), $seconds);
    }

    /**
     * @return bool
     */
    public function isInvalid(): bool
    {
        return $this->invalid;
    }

    /**
     * @param bool $invalid
     */
    public function setInvalid(bool $invalid): void
    {
        $this->invalid = $invalid;
    }

    /**
     * @return bool
     */
    public function isFullDay(): bool
    {
        return $this->fullDay;
    }

    /**
     * @param bool $isFullDay
     *
     * @return void
     */
    public function setFullDay(bool $isFullDay)
    {
        $this->fullDay = $isFullDay;
    }

    /**
     * @param mixed $dateTime
     *
     * @return void
     */
    public function setFromObject($dateTime)
    {
        if($dateTime) {
            if(isset($dateTime['year']) && isset($dateTime['month']) && isset($dateTime['day'])) {
                $this->setYear($dateTime['year']);
                $this->setMonth($dateTime['month']);
                $this->setDay($dateTime['day']);

                if(isset($dateTime['hour']) && isset($dateTime['minute']) && isset($dateTime['seconds'])) {
                    $this->setFullTime($dateTime['hour'], $dateTime['minute'], $dateTime['seconds']);
                    $this->setFullDay(false);
                } else {
                    $this->setFullDay(true);
                }

                return;
            }
        }

        $this->invalid = true;
    }

    /**
     * @param int $dateInterval
     * @param ExtendedDateTime $target
     *
     * @return Iterator
     */
    public function getDateTimesBetween(int $dateInterval, ExtendedDateTime $target): Iterator
    {
        $start = $target;
        $end = $this;
        if($end->isEarlierThan($start)) {
            $start = $this;
            $end = $target;
        }

        $iteratingDateTime = $start->copy();

        if($dateInterval == EXTDATETIME_INTERVAL_DAY) {
            while ($iteratingDateTime->isEarlierThan($end)) {
                $copy = $iteratingDateTime->copy();
                $iteratingDateTime->setDay($iteratingDateTime->getDay() + 1);

                if(!$start->isSameDate($copy) && $iteratingDateTime->isEarlierThan($end)) {
                    $copy->setFullDay(true);
                }

                yield $copy;
            }
        }
    }

    /**
     * @return ExtendedDateTime
     */
    public function copy(): ExtendedDateTime
    {
        $copy = new ExtendedDateTime();
        $copy->setYear($this->getYear());
        $copy->setMonth($this->getMonth());
        $copy->setDay($this->getDay());
        $copy->setHour($this->getHour());
        $copy->setMinute($this->getMinute());
        $copy->setSeconds($this->getSeconds());
        $copy->setFullDay($this->isFullDay());
        $copy->setInvalid($this->isInvalid());
        return $copy;
    }

    /**
     * @param ExtendedDateTime $dateTime
     *
     * @return bool
     */
    public function isEarlierThan(ExtendedDateTime $dateTime): bool
    {
        if($this->isFullDay() || $dateTime->isFullDay()) {
            return $this->getTimestampBeginOfDay() <= $dateTime->getTimestampBeginOfDay();
        }

        return $this->getTimestamp() <= $dateTime->getTimestamp();
    }

    /**
     * @return DateTime
     */
    public function getInternalDateTime(): DateTime
    {
        return $this->internalDateTime;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        if($this->isFullDay()) {
            return [
                "year" => $this->getYear(),
                "month" => $this->getMonth(),
                "day" => $this->getDay()
            ];
        }

        return [
            "year" => $this->getYear(),
            "month" => $this->getMonth(),
            "day" => $this->getDay(),
            "hour" => $this->getHour(),
            "minute" => $this->getMinute(),
            "seconds" => $this->getSeconds()
        ];
    }
}