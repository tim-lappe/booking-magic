<?php


namespace TLBM\Admin\FormEditor\FormItemSettingsElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


class Input extends SettingsType {

	public $input_type = "text";
	public $must_unique = false;

	public function __construct( $name, $title, $input_type = "text", $settings_printing = false, $default_value = "", $must_unique = false) {
		parent::__construct( $name, $title,$settings_printing, $default_value );
		$this->input_type = $input_type;
		$this->must_unique = $must_unique;
	}

	public function GetEditorOutput(): string {
		$disabled = $this->readonly ? "disabled='disabled'" : "";
		$unique = $this->must_unique ? "unique": "";
		return "<label>" . $this->title . "<br><input " . $unique . " " . $disabled . " type='" . $this->input_type. "' name='" . $this->name . "'></label>";
	}
}