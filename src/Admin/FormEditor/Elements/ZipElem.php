<?php


namespace TLBM\Admin\FormEditor\Elements;


use TLBM\Admin\FormEditor\FrontendGeneration\InputGenerator;

class ZipElem extends FormInputElem {

	public function __construct() {
		parent::__construct( "field_zip_code", __("ZIP", TLBM_TEXT_DOMAIN) );

		$this->menu_category = __("Predefined fields", TLBM_TEXT_DOMAIN);
		$this->description = __("number field for the zip-code", TLBM_TEXT_DOMAIN);

		$name_setting = $this->GetSettingsType("name");
		$name_setting->default_value = "zip";
		$name_setting->readonly = true;

		$title_setting = $this->GetSettingsType("title");
		$title_setting->default_value = __("Zip", TLBM_TEXT_DOMAIN);

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