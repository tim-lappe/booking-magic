<?php


namespace TLBM\Utilities;


use DateTime;
use TLBM\Entity\RulePeriod;

class PeriodsTools {


	/**
	 * @param RulePeriod $period
	 * @param DateTime $dateTime
	 *
	 * @return bool
	 */
	public static function IsDateTimeInPeriod(RulePeriod $period, DateTime $dateTime ): bool {
        $from_day = $period->GetFromDay();
        $from_month = $period->GetFromMonth();
        $from_year = $period->GetFromYear();

        $to_day = $period->GetToDay();
        $to_month = $period->GetToMonth();
        $to_year = $period->GetToYear();

        if(!$from_year) {
            $from_year = $dateTime->format("Y");
        }
        if(!$to_year) {
            $to_year = $dateTime->format("Y");
        }

        $fromDt = new DateTime();
        $fromDt->setDate($from_year, $from_month, $from_day - 1);
        $toDt = new DateTime();
        $toDt->setDate($to_year, $to_month, intval($to_day));
        return $fromDt->getTimestamp() <= $dateTime->getTimestamp() && $toDt->getTimestamp() >= $dateTime->getTimestamp();
	}

	/**
	 * @param RulePeriod[] $periods
	 * @param DateTime $dateTime
	 *
	 * @return bool
	 */
	public static function IsDateTimeInPeriodCollection( array $periods, DateTime $dateTime ): bool {
		foreach ($periods as $item) {
			if(self::IsDateTimeInPeriod($item, $dateTime)) {
				return true;
			}
		}

		return sizeof($periods) == 0;
	}
}