<?php


namespace TLBM\Admin\FormEditor\Elements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use TLBM\Admin\FormEditor\ItemSettingsElements\Input;
use TLBM\Admin\FormEditor\ItemSettingsElements\Select;
use TLBM\Admin\FormEditor\ItemSettingsElements\SettingsPrinting;

abstract class FormInputElem extends FormElem {
	public function __construct( $name, $title ) {
		parent::__construct( $name, $title );

		$this->settings[] = new Input( "title", __("Title", TLBM_TEXT_DOMAIN), "text", $this->title);
		$this->settings[] = new Input( "name", __("Name", TLBM_TEXT_DOMAIN), "text", str_replace(" ", "_", strtolower($this->title)), true);

		$this->settings[] = new Select( "required", __("Requied", TLBM_TEXT_DOMAIN),
			array("yes" => __("Yes", TLBM_TEXT_DOMAIN), "no" => __("No", TLBM_TEXT_DOMAIN)), "no");

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