<?php


namespace TLBM\Admin\FormEditor;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


class Formeditor {

    /**
     * @return array
     */
    public static function GetFormElements(): array {
        $formelements_arr = array();

        foreach(FormElementsCollection::$formelements as $elem) {
            $elem->settings_output = array();
            foreach($elem->settings as $setting) {
                $elem->settings_output[] = $setting->GetEditorOutput();
            }

            $formelements_arr[] = get_object_vars($elem);
        }

        return $formelements_arr;
    }
}