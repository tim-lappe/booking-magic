<?php


namespace TL_Booking\Admin\FormEditor\FormItemSettingsElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


class Select extends SettingsType {

	public $key_values;

	public function __construct($name, $title, $key_values, $settings_printing = false, $default_value = "") {
		$this->key_values = $key_values;
		parent::__construct($name, $title, $settings_printing, $default_value);
	}

	public function GetEditorOutput(): string {
		$out = "<label>" . $this->title . "<br><select name=\"$this->name\">";
		foreach ($this->key_values as $key => $value) {
			if(is_array($value)) {
				$out .= "<optgroup label='".$key."'>";
				foreach ($value as $key_inner => $value_inner) {
					$out .= "<option value=\"".$key_inner."\">" . $value_inner. "</option>";
				}
				$out .= "</optgroup>";
			} else {
				$out .= "<option value=\"".$key."\">" . $value. "</option>";
			}
		}
		$out .=	"</select></label>";
		return $out;
	}
}