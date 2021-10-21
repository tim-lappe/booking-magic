<?php


namespace TLBM\Admin\FormEditor\FormElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use TLBM\Admin\FormEditor\FrontendGeneration\InputGenerator;

final class EmailElem extends FormInputElem {
	public function __construct() {
		parent::__construct("field_email",  __("E-Mail", TLBM_TEXT_DOMAIN) );

		$this->description = __("A field in which the user can enter an e-mail", TLBM_TEXT_DOMAIN);

		$this->GetSettingsType("name")->default_value = "email";
		$this->GetSettingsType("title")->default_value = __("E-Mail", TLBM_TEXT_DOMAIN);
	}

    /**
     * @param      $data_obj
     * @param null $insert_child
     *
     * @return mixed
     */
	public function GetFrontendOutput($data_obj, $insert_child = null) {
		return InputGenerator::GetFormControl("email", $data_obj->title, $data_obj->name, $data_obj->required == "yes");
	}
}

