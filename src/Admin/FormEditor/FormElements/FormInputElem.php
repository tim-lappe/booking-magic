<?php


namespace TLBM\Admin\FormEditor\FormElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use TLBM\Admin\FormEditor\FormItemSettingsElements\Input;
use TLBM\Admin\FormEditor\FormItemSettingsElements\Select;
use TLBM\Admin\FormEditor\FormItemSettingsElements\SettingsPrinting;

abstract class FormInputElem extends FormElem {
	public function __construct( $name, $title ) {
		parent::__construct( $name, $title );

		$this->editor_output = "<div class='tlbm-form-item-box'><span class='tlbm-form-settings-print-title'>" . $title . "</span><span class='tlbm-form-settings-print-subtitle'></span><span class='tlbm-form-settings-print-required'></span></div>";

		$this->settings[] = new Input( "title", __("Title", TLBM_TEXT_DOMAIN), "text", new SettingsPrinting("title", array(), array("" => $title)));
		$this->settings[] = new Input( "name", __("Name", TLBM_TEXT_DOMAIN), "text", new SettingsPrinting("subtitle"));

		$this->settings[] = new Select( "required", __("Requied", TLBM_TEXT_DOMAIN),
			array("yes" => __("Yes", TLBM_TEXT_DOMAIN), "no" => __("No", TLBM_TEXT_DOMAIN)),
			new SettingsPrinting("required", array(), array("yes" => __("Required", TLBM_TEXT_DOMAIN), "no" => "")), "no");

        $this->has_user_input = true;
	}

	public function Validate($form_data, $input_vars): bool {
        if(isset($form_data['name'])) {
            $name = $form_data['name'];
            if(isset($input_vars[$name])) {
                return !empty($input_vars[$name]);
            }
        }
        return false;
    }
}