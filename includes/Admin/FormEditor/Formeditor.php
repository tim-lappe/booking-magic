<?php


namespace TL_Booking\Admin\FormEditor;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use TL_Booking\Admin\FormEditor\FormElements\FormElem;

class Formeditor {

    public static function InsertFormelementsIntoScript($handle) {
		$formelements_arr = array();

		foreach(FormElementsCollection::$formelements as $elem) {
			$elem->settings_output = array();
			foreach($elem->settings as $setting) {
				$elem->settings_output[] = $setting->GetEditorOutput();
			}

			$formelements_arr[] = get_object_vars($elem);
		}
		wp_localize_script($handle, "tlbm_form_elements", $formelements_arr);
	}
}