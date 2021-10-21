<?php


namespace TLBM\Admin\FormEditor\FormItemSettingsElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


class SettingsStyleEdit {

	public $style_name;
	public $selector;

	public function __construct($style_name, $selector = "") {
		$this->style_name = $style_name;
		$this->selector = $selector;
	}
}