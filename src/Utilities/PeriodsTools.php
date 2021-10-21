<?php


namespace TLBM\Utilities;


use TLBM\Model\PeriodCollection;

class PeriodsTools {


	/**
	 * @param $period
	 * @param \DateTime $dateTime
	 *
	 * @return bool
	 */
	public static function IsDateTimeInPeriod( $period, \DateTime $dateTime ): bool {
		if($period->type == "date") {
			$from = $period->from;
			$to = $period->to;
			if(empty($from->year)) {
				$from->year = $dateTime->format("Y");
			}
			if(empty($to->year)) {
				$to->year = $dateTime->format("Y");
			}

			$fromDt = new \DateTime();
			$fromDt->setDate($from->year, $from->month, $from->day - 1);
			$toDt = new \DateTime();
			$toDt->setDate($to->year, $to->month, intval($to->day));
			return $fromDt->getTimestamp() <= $dateTime->getTimestamp() && $toDt->getTimestamp() >= $dateTime->getTimestamp();
		}

		return false;
	}

	/**
	 * @param PeriodCollection $period_collection
	 * @param \DateTime $dateTime
	 *
	 * @return bool
	 */
	public static function IsDateTimeInPeriodCollection( PeriodCollection $period_collection, \DateTime $dateTime ): bool {
		foreach ($period_collection->period_list as $item) {
			if(self::IsDateTimeInPeriod($item, $dateTime)) {
				return true;
			}
		}
		return sizeof($period_collection->period_list) == 0;
	}
}