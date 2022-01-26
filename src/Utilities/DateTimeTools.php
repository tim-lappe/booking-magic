<?php

namespace TLBM\Utilities;

use DateTime;
use TLBM\Utilities\Contracts\DateTimeToolsInterface;

if ( ! defined('ABSPATH')) {
    return;
}

class DateTimeTools implements DateTimeToolsInterface
{

    public function __construct()
    {
    }

    /**
     * @param $timestamp
     *
     * @return string
     */
    public function format($timestamp): string
    {
        return date_i18n(DateTimeTools::getDateFormat(), intval($timestamp));
    }

    /**
     * @return mixed
     */
    public function getDateFormat(): string
    {
        $format = get_option('date_format');
        if (empty($format)) {
            return "d.m.Y";
        }

        return get_option('date_format');
    }

    /**
     * @param $timestamp
     *
     * @return string
     */
    public function formatWithTime($timestamp): string
    {
        return date_i18n(DateTimeTools::getDateFormat() . " " . self::getTimeFormat(), $timestamp);
    }

    /**
     * @return mixed
     */
    public function getTimeFormat()
    {
        return get_option('time_format');
    }

    /**
     * @param int $years
     * @param int $days
     * @param int $hours
     * @param int $minutes
     *
     * @return float|int
     */
    public function fromTimepartsToMinutes(int $years = 0, int $days = 0, int $hours = 0, int $minutes = 0)
    {
        return ($years * 365 * 24 * 60) + ($days * 24 * 60) + ($hours * 60) + $minutes;
    }

    /**
     * @param $minutes
     *
     * @return array|int[]
     */
    public function fromMinutesToTimeparts($minutes): array
    {
        if (is_int($minutes)) {
            $dtF = new DateTime();
            $dtT = new DateTime();
            $dtF->setTimestamp(time());
            $dtT->setTimestamp(time() + ($minutes * 60));

            $years = $dtF->diff($dtT)->format('%y');
            $days  = $dtF->diff($dtT)->format('%a');
            $days  -= $years * 365;

            $hours   = $dtF->diff($dtT)->format('%h');
            $minutes = $dtF->diff($dtT)->format('%i');

            return array(
                "years"   => $years,
                "days"    => $days,
                "hours"   => $hours,
                "minutes" => $minutes
            );
        } else {
            return array(
                "years"   => 0,
                "days"    => 0,
                "hours"   => 0,
                "minutes" => 0
            );
        }
    }
}