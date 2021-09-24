<?php


namespace TL_Booking\Admin\WpForm\RuleActionFields;


use TL_Booking\Output\Html\FormContents;

if (!defined('ABSPATH')) {
    return;
}

class DaySlotAction extends RuleActionFieldBase {

    public function __construct() {
        parent::__construct("day-slot", __("Add Day Slot", TLBM_TEXT_DOMAIN), "");

        $this->formHtml = '
        <div style="display: flex">
        	<div>
        		<small>'.__("Weekday(s)", TLBM_TEXT_DOMAIN).'</small><br>
	            <select name="weekday">
	                '.FormContents::GetWeekdaysSelectOptions().'
				</select>
			</div>
			<div style="margin-left: 20px">
				<small>'.__("Capacity", TLBM_TEXT_DOMAIN).'</small><br>
				<div style="display: flex">
					<select name="capacity_mode">
		                '.FormContents::GetCapacityModeSelectOptions() . '
					</select>
					<input type="number" name="capacity"> 
				</div>
			</div>
        </div>
        ';
    }
}