<?php


namespace TLBM\Admin\FormEditor\FormElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use TLBM\Admin\FormEditor\FormItemSettingsElements\SettingsType;

abstract class FormElem {

	/**
	 * @var string
	 */
	public $title;

	/**
	 * @var string
	 */
	public $unique_name;

	/**
	 * @var SettingsType[]
	 */
	public $settings = array();

	/**
	 * @var string
	 */
	public $editor_output;

	/**
	 * @var string
	 */
	public $menu_category = "General";

	/**
	 * @var array
	 */
	public $settings_output;

	/**
	 * @var string
	 */
	public $description;

    /**
     * @var bool
     */
	public $has_user_input = false;

	public function __construct($name, $title) {
		$this->title = $title;
		$this->unique_name = $name;
		$this->editor_output = "<div class='tlbm-form-item-box'><span class='tlbm-form-settings-print-title'>" . $title . "</span><span class='tlbm-form-settings-print-subtitle'></span></div>";
		$this->menu_category = __("General", TLBM_TEXT_DOMAIN);
		$this->description = "";
	}

	/**
	 * @param $name
	 *
	 * @return false|SettingsType
	 */
	public function GetSettingsType($name)  {
		foreach($this->settings as $setting) {
			if($setting->name == $name) {
				return $setting;
			}
		}

		return false;
	}

	/**
	 * @param $data_obj
	 * @param null|callable $insert_child
	 *
	 * @return mixed
	 */
	public abstract function GetFrontendOutput($data_obj, $insert_child = null);

    /**
     * @param $form_data
     * @param $input_vars
     *
     * @return bool
     */
	public function Validate($form_data, $input_vars): bool {
	    return true;
    }
}