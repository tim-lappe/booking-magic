<?php

namespace TLBM\Utilities;

use DateTime;
use InvalidArgumentException;
use Iterator;
use JsonSerializable;
use TLBM\ApiUtils\Contracts\OptionsInterface;
use TLBM\ApiUtils\Contracts\TimeUtilsInterface;
use TLBM\MainFactory;

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

    /**
     * @param int|null $timestamp
     * @param bool $fullDay
     */
    public function __construct(?int $timestamp = null, bool $fullDay = false)
    {
        $timeutils = MainFactory::get(TimeUtilsInterface::class);

        $this->internalDateTime = new DateTime();
        $this->internalDateTime->setTimezone($timeutils->getTimezone());

        if($timestamp !== null) {
            $this->internalDateTime->setTimestamp($timestamp);
        } else {
            $this->internalDateTime->setTimestamp($timeutils->time());
        }

        $this->setFullDay($fullDay);
    }

    public function __toString()
    {
        return $this->format();
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
        $extDateTime->setFullTime(0, 0, 0);
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
    public function isEqualTo(ExtendedDateTime $dateTime): bool
    {
        if($this->fullDay != $dateTime->fullDay) {
            return false;
        }

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
     * @return string
     */
    public function format(): string
    {
        $options = MainFactory::get(OptionsInterface::class);
        $timeUtils = MainFactory::get(TimeUtilsInterface::class);

        $dateFormat = $options->getOption('date_format');
        $timeFormat = $options->getOption('time_format');

        if (empty($dateFormat)) {
            $dateFormat = "d.m.Y";
        }

        $shiftetTimestamp = $this->getTimestamp();
        $shiftetTimestamp += $this->internalDateTime->getOffset();

        if($this->isFullDay()) {
            return $timeUtils->formatI18n($dateFormat, $shiftetTimestamp);
        } else {
            return $timeUtils->formatI18n($dateFormat . " " . $timeFormat, $shiftetTimestamp);
        }
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
     * @return int
     */
    public function getHour(): ?int
    {
        if($this->isFullDay()) {
            return 0;
        }

        return intval($this->internalDateTime->format("H"));
    }

    /**
     * @param int $hour
     */
    public function setHour(int $hour): void
    {
        $this->internalDateTime->setTime($hour, $this->getMinute(), $this->getDay());
    }

    /**
     * @return int
     */
    public function getMinute(): int
    {
        if($this->isFullDay()) {
            return 0;
        }

        return intval($this->internalDateTime->format("i"));
    }

    /**
     * @param int $minute
     */
    public function setMinute(int $minute): void
    {
        $this->internalDateTime->setTime($this->getHour(), $minute, $this->getDay());
    }

    /**
     * @return int
     */
    public function getSeconds(): int
    {
        if($this->isFullDay()) {
            return 0;
        }

        return intval($this->internalDateTime->format("s"));
    }

    /**
     * @param int $seconds
     */
    public function setSeconds(int $seconds): void
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
        if($isFullDay) {
            $this->setFullTime(0, 0, 0);
        }
    }

    /**
     * @param mixed $dateTime
     *
     * @return ?ExtendedDateTime
     */
    public function setFromObject($dateTime): ?ExtendedDateTime
    {
        if($dateTime) {
            if(!is_array($dateTime)) {
                $dateTime = (array)$dateTime;
            }

            if(isset($dateTime['year']) && isset($dateTime['month']) && isset($dateTime['day'])) {
                $this->setYear(intval($dateTime['year']));
                $this->setMonth(intval($dateTime['month']));
                $this->setDay(intval($dateTime['day']));

                if(isset($dateTime['hour'])) {
                    $min = $dateTime['minute'] ?? 0;
                    $seconds =  $dateTime['seconds'] ?? 0;

                    $this->setFullTime(intval($dateTime['hour']), intval($min), intval($seconds));
                    $this->setFullDay(false);
                } else {
                    $this->setFullDay(true);
                }

                return $this;
            }
        }

        throw new InvalidArgumentException("Invalid date time object: " . var_export($dateTime, true));
    }

    /**
     * @param string $dateInterval
     * @param ExtendedDateTime $target
     *
     * @return Iterator
     */
    public function getDateTimesBetween(string $dateInterval, ExtendedDateTime $target): Iterator
    {
        $start = $target;
        $end = $this;
        if($end->isEarlierThan($start)) {
            $start = $this;
            $end = $target;
        }

        $iteratingDateTime = $start->copy();

        if($dateInterval == TLBM_EXTDATETIME_INTERVAL_DAY) {
            while ($iteratingDateTime->isEarlierThan($end)) {
                $copy = $iteratingDateTime->copy();
                $iteratingDateTime->setDay($iteratingDateTime->getDay() + 1);

                if( !$start->isEqualTo($copy) && $iteratingDateTime->isEarlierThan($end)) {
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