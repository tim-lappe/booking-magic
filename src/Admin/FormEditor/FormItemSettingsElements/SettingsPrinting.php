<?php


namespace TLBM\Admin\FormEditor\FormItemSettingsElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


class SettingsPrinting {

	public $output_var = "";
	public array $style_editings = array();
	public array $replacings = array();

	public function __construct($output_var = "", $style_editings = array(), $replacings = array()) {
		$this->output_var = $output_var;
		$this->style_editings = $style_editings;
		$this->replacings = $replacings;
	}
}