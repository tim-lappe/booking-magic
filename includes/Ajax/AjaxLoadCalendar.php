<?php


namespace TL_Booking\Ajax;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use DateTime;
use TL_Booking\Calendar\CalendarManager;
use TL_Booking\Output\Calendar\CalendarOutput;

class AjaxLoadCalendar extends AjaxBase {

	function RegisterAjaxAction() {
		$this->AddAjaxAction("loadCalendar");
	}

	function ApiRequest($data) {
		if(isset($data['id'])) {
			$calendar = CalendarManager::GetCalendar($data['id']);
			if($calendar) {
				$time = time();
				if(isset($data['focused_tstamp'])) {
				    $time = $data['focused_tstamp'];
				}

				$date = new DateTime();
				$date->setTimestamp($time);

				$printer = CalendarOutput::GetCalendarPrinterForCalendar($calendar);
				$output = $printer->GetOutput($data);

				die(json_encode(array(
				    "html" => $output,
                    "data" => $data
                )));
			}
		}
	}
}