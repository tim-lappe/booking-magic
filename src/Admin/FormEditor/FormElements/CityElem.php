<?php


namespace TLBM\Admin\FormEditor\FormElements;


use TLBM\Admin\FormEditor\FrontendGeneration\InputGenerator;

class CityElem extends FormInputElem {

	public function __construct() {
		parent::__construct( "field_city", __("City", TLBM_TEXT_DOMAIN) );

		$this->menu_category = __("Predefined fields", TLBM_TEXT_DOMAIN);
		$this->description = __("text field for the name of the city", TLBM_TEXT_DOMAIN);

		$name_setting = $this->GetSettingsType("name");
		$name_setting->default_value = "city";
		$name_setting->readonly = true;

		$title_setting = $this->GetSettingsType("title");
		$title_setting->default_value = __("City", TLBM_TEXT_DOMAIN);

		$required = $this->GetSettingsType("required");
		$required->default_value = "yes";
		$required->readonly = true;
	}

	/**
	 * @inheritDoc
	 */
	public function GetFrontendOutput( $data_obj, $insert_child = null ) {
		return InputGenerator::GetFormControl("text", $data_obj->title, $data_obj->name, $data_obj->required == "yes");
	}
}