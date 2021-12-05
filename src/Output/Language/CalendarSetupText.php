<?php


namespace TLBM\Output\Language;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

class CalendarSetupText {

	public static function GetBookingModeText($option): string {
		switch ($option) {
			case "only_date":
				return "<strong>Date</strong><br>
						Allows the User to select a Date";
			case "date_range":
				return "<strong>Date Range</strong><br>
						The user can select a range of dates.";
			case "flexible_datetime":
				return "<strong>Flexible Datetime</strong><br>
						The user can select a any time of the choosen date";
			case "slotted_datetime":
				return "<strong>Slotted Datetime</strong><br>
						The user can choose from pre-defined Timeslots of the choosen date";
		}

        return "";
	}
}