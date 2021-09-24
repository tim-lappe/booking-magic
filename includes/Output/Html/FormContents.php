<?php


namespace TL_Booking\Output\Html;


class FormContents {

	public static function GetWeekdaysSelectOptions($selected = ""): string {
		return '<optgroup label="'.__("Multiple Weekdays", TLBM_TEXT_DOMAIN).'">
	                <option '.($selected == "every_day" ? 'selected="selected"':'').' value="every_day">'.__("Every Day", TLBM_TEXT_DOMAIN).'</option>
	                <option '.($selected == "mo_to_fr" ? 'selected="selected"':'').' value="mo_to_fr">'.__("Monday to Friday", TLBM_TEXT_DOMAIN).'</option>
	                <option '.($selected == "sat_and_sun" ? 'selected="selected"':'').' value="sat_and_sun">'.__("Saturday and Sunday", TLBM_TEXT_DOMAIN).'</option>
				</optgroup>
                <optgroup label="'.__("Single Weekdays", TLBM_TEXT_DOMAIN).'">
	                <option '.($selected == "monday" ? 'selected="selected"':'').' value="monday">'.__("Monday", TLBM_TEXT_DOMAIN).'</option>
	                <option '.($selected == "tuesday" ? 'selected="selected"':'').' value="tuesday">'.__("Tuesday", TLBM_TEXT_DOMAIN).'</option>
	                <option '.($selected == "wednesday" ? 'selected="selected"':'').' value="wednesday">'.__("Wednesday", TLBM_TEXT_DOMAIN).'</option>
	                <option '.($selected == "thursday" ? 'selected="selected"':'').' value="thursday">'.__("Thursday", TLBM_TEXT_DOMAIN).'</option>
	                <option '.($selected == "friday" ? 'selected="selected"':'').' value="friday">'.__("Friday", TLBM_TEXT_DOMAIN).'</option>
	                <option '.($selected == "saturday" ? 'selected="selected"':'').' value="saturday">'.__("Saturday", TLBM_TEXT_DOMAIN).'</option>
	                <option '.($selected == "sunday" ? 'selected="selected"':'').' value="sunday">'.__("Sunday", TLBM_TEXT_DOMAIN).'</option>
                </optgroup>';
	}

	public static function GetCapacityModeSelectOptions($selected = ""): string {
		return '<option '.($selected == "set" ? 'selected="selected"':'').' value="set">'.__("Set", TLBM_TEXT_DOMAIN).'</option>
                <option '.($selected == "add" ? 'selected="selected"':'').' value="add">'.__("Add", TLBM_TEXT_DOMAIN).'</option>
                <option '.($selected == "subtract" ? 'selected="selected"':'').' value="subtract">'.__("Subtract", TLBM_TEXT_DOMAIN).'</option>';
	}

	public static function GetTimeHoursSelectOptions($selected = ""): string {
		$html = '';
		for($i = 0; $i < 24; $i++) {
			$html .= '<option '.($selected == "'.$i.'" ? 'selected="selected"':'').' value="'.$i.'">'.$i.'</option>';
        }
		return $html;
	}

	public static function GetTimeMinutesSelectOptions($selected = ""): string {
		$html = '';
		for($i = 0; $i <= 59; $i++) {
			$html .= '<option '.($selected == "'.$i.'" ? 'selected="selected"':'').' value="'.$i.'">'.$i.'</option>';
		}
		return $html;
	}
}