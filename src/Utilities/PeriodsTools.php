<?php


namespace TLBM\Utilities;


use DateTime;
use TLBM\Entity\RulePeriod;
use TLBM\Utilities\Contracts\PeriodsToolsInterface;

class PeriodsTools implements PeriodsToolsInterface
{


    public function __construct()
    {
    }

    /**
     * @param RulePeriod[] $periods
     * @param DateTime $dateTime
     *
     * @return bool
     */
    public function isDateTimeInPeriodCollection(array $periods, DateTime $dateTime): bool
    {
        foreach ($periods as $item) {
            if ($this->isDateTimeInPeriod($item, $dateTime)) {
                return true;
            }
        }

        return sizeof($periods) == 0;
    }

    /**
     * @param RulePeriod $period
     * @param DateTime $dateTime
     *
     * @return bool
     */
    public function isDateTimeInPeriod(RulePeriod $period, DateTime $dateTime): bool
    {
        $fromDateTime = new DateTime();
        $fromDateTime->setTimestamp($period->getFromTstamp());

        $toDateTime = new DateTime();
        $toDateTime->setTimestamp($period->getToTstamp());

        $from_day   = $fromDateTime->format("d");
        $from_month = $fromDateTime->format("m");
        $from_year  = $fromDateTime->format("Y");

        $to_day   = $toDateTime->format("d");
        $to_month = $toDateTime->format("m");
        $to_year  = $toDateTime->format("Y");

        if ( !$from_year) {
            $from_year = $dateTime->format("Y");
        }
        if ( !$to_year) {
            $to_year = $dateTime->format("Y");
        }

        $fromDt = new DateTime();
        $fromDt->setDate($from_year, $from_month, $from_day - 1);
        $toDt = new DateTime();
        $toDt->setDate($to_year, $to_month, intval($to_day));

        return $fromDt->getTimestamp() <= $dateTime->getTimestamp() && $toDt->getTimestamp() >= $dateTime->getTimestamp();
    }
}