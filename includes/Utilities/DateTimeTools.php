<?php

namespace TL_Booking\Utilities;

use DateTime;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

class DateTimeTools {

	/**
	 * @param $timestamp
	 *
	 * @return string
	 */
	public static function Format($timestamp): string {
		return date_i18n(DateTimeTools::GetDateFormat(), intval($timestamp));
	}

	/**
	 * @param $timestamp
	 *
	 * @return string
	 */
	public static function FormatWithTime($timestamp): string {
		return date_i18n( DateTimeTools::GetDateFormat() . " " . self::GetTimeFormat(), $timestamp);
	}

	/**
	 * @return mixed
	 */
	public static function GetDateFormat(): string {
		$format = get_option( 'date_format' );
		if(empty($format)) {
			return "d.m.Y";
		}
		return get_option( 'date_format' );
	}
	/**
	 * @return mixed
	 */
	public static function GetTimeFormat() {
		return get_option( 'time_format' );
	}

	public static function FromTimepartsToMinutes($years = 0, $days = 0, $hours = 0, $minutes = 0) {
		return ($years * 365 * 24 * 60) + ($days * 24 * 60) + ($hours * 60) + $minutes;
	}

	public static function FromMinutesToTimeparts($minutes): array {
		if(is_int($minutes)) {
			$dtF   = new DateTime();
			$dtT   = new DateTime();
			$dtF->setTimestamp(time());
			$dtT->setTimestamp(time() + ($minutes * 60));

			$years = $dtF->diff( $dtT )->format( '%y' );
			$days = $dtF->diff( $dtT )->format( '%a' );
			$days -= $years * 365;

			$hours = $dtF->diff( $dtT )->format( '%h' );
			$minutes = $dtF->diff( $dtT )->format( '%i' );

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