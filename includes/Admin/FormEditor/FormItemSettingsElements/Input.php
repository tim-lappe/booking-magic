<?php


namespace TL_Booking\Admin\FormEditor\FormItemSettingsElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


class Input extends SettingsType {

	public $input_type = "text";

	public function __construct( $name, $title, $input_type = "text", $settings_printing = false, $default_value = "") {
		parent::__construct( $name, $title,$settings_printing, $default_value );
		$this->input_type = $input_type;
	}

	public function GetEditorOutput(): string {
		$disabled = $this->readonly ? "disabled='disabled'" : "";
		return "<label>" . $this->title . "<br><input " . $disabled . " type='" . $this->input_type. "' name='" . $this->name . "'></label>";
	}
}