<?php


namespace TLBM\Admin\WpForm\RuleActionFields;


use TLBM\Output\Html\FormContents;

if ( !defined('ABSPATH')) {
    return;
}

class TimeSlotAction extends RuleActionFieldBase
{

    public function __construct()
    {
        parent::__construct("time-slot", __("Add Time Slot", TLBM_TEXT_DOMAIN), "");

        $this->formHtml = '
        <div style="display: flex">
        	<div>
        		<small>' . __("Weekday(s)", TLBM_TEXT_DOMAIN) . '</small><br>
	            <select name="weekday">
	                ' . FormContents::GetWeekdaysSelectOptions() . '
				</select>
			</div>
			<div style="margin-left: 20px">
				<small>' . __("Timeslot", TLBM_TEXT_DOMAIN) . '</small><br>
				<div style="display: flex">
					<select name="hour">
						' . FormContents::GetTimeHoursSelectOptions() . '
					</select>
					<span>&nbsp;:&nbsp;</span>
					<select name="minutes">
						' . FormContents::GetTimeMinutesSelectOptions() . '
					</select>
				</div>
			</div> 
			<div style="margin-left: 20px">
				<small>' . __("Capacity", TLBM_TEXT_DOMAIN) . '</small><br>
				<div style="display: flex">
					<select name="capacity_mode">
		                ' . FormContents::GetCapacityModeSelectOptions() . '
					</select>
					<input type="number" min="0" name="capacity"> 
				</div>
			</div>
        </div>
        ';
    }
}