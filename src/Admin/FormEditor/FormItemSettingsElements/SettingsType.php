<?php


namespace TLBM\Admin\FormEditor\FormItemSettingsElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


abstract class SettingsType {

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $title;

	/**
	 * @var false|SettingsPrinting
	 */
	public $settings_printing;

	/**
	 * @var string
	 */
	public $default_value;

	/**
	 * @var bool
	 */
	public $readonly;



	/**
	 * SettingsType constructor.
	 *
	 * @param $name
	 * @param $title
	 * @param false|SettingsPrinting $settings_printing
	 * @param string $default_value
	 * @param bool $readonly
	 */
	public function __construct($name, $title, $settings_printing = false, string $default_value = "", bool $readonly = false) {
		$this->name = $name;
		$this->title = $title;
		$this->settings_printing = $settings_printing;
		$this->default_value = $default_value;
		$this->readonly = $readonly;
	}

	public abstract function GetEditorOutput();
}