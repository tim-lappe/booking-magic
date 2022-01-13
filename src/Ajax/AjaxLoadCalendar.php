<?php


namespace TLBM\Ajax;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use DateTime;
use TLBM\Model\CalendarGroup;
use TLBM\Model\CalendarSelection;
use TLBM\Output\Calendar\CalendarOutput;

class AjaxLoadCalendar extends AjaxBase {

	function RegisterAjaxAction() {
		$this->AddAjaxAction("loadCalendar");
	}

	function ApiRequest($data) {
		if(isset($data['view']) && isset($data['id'])) {
			$time = time();
			if(isset($data['focused_tstamp'])) {
			    $time = $data['focused_tstamp'];
			}

			$date = new DateTime();
			$date->setTimestamp($time);

			$group = CalendarGroup::FromCalendarOrGroupId($data['id']);
			$printer = CalendarOutput::GetCalendarPrinterForCalendarGroup( $group );
			$output  = $printer->GetOutput( $data );

			die(json_encode(array(
			    "html" => $output,
                "data" => $data
            )));
		}
	}
}