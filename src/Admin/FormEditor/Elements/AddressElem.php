<?php


namespace TLBM\Admin\FormEditor\Elements;


use TLBM\Admin\FormEditor\FrontendGeneration\InputGenerator;

class AddressElem extends FormInputElem {

	public function __construct() {
		parent::__construct( "field_address_line", __("Address", TLBM_TEXT_DOMAIN) );

		$this->menu_category = __("Predefined fields", TLBM_TEXT_DOMAIN);
		$this->description = __("text field for the address-line", TLBM_TEXT_DOMAIN);

		$name_setting = $this->GetSettingsType("name");
		$name_setting->default_value = "address";
		$name_setting->readonly = true;

		$title_setting = $this->GetSettingsType("title");
		$title_setting->default_value = __("Address", TLBM_TEXT_DOMAIN);

		$required = $this->GetSettingsType("required");
		$required->default_value = "yes";
	}

	/**
	 * @inheritDoc
	 */
	public function GetFrontendOutput($data_obj, callable $insert_child = null ): string {
		return InputGenerator::GetFormControl("text", $data_obj->title, $data_obj->name, $data_obj->required == "yes");
	}
}