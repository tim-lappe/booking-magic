<?php


namespace TLBM\Admin\FormEditor\ItemSettingsElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


class Input extends ElementSetting {

	public $input_type = "text";

	public function __construct( $name, $title, $input_type = "text", $default_value = "", $must_unique = false) {
		parent::__construct( $name, $title, $default_value );
		$this->input_type = $input_type;
		$this->must_unique = $must_unique;
        $this->type = "input";
	}

	public function GetEditorOutput(): string {
		$disabled = $this->readonly ? "disabled='disabled'" : "";
		$unique = $this->must_unique ? "unique": "";
		return "<label>" . $this->title . "<br><input " . $unique . " " . $disabled . " type='" . $this->input_type. "' name='" . $this->name . "'></label>";
	}
}