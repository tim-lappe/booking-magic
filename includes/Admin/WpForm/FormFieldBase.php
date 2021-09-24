<?php


namespace TL_Booking\Admin\WpForm;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

abstract class FormFieldBase {

	public $name;
	public $value;
	public $title;

	public function __construct($name, $title, $value = "") {
		$this->name = $name;
		$this->value = $value;
		$this->title = $title;
	}

	abstract function OutputHtml();
}