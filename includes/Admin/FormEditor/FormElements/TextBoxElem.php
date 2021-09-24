<?php


namespace TL_Booking\Admin\FormEditor\FormElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TL_Booking\Admin\FormEditor\FrontendGeneration\InputGenerator;

final class TextBoxElem extends FormInputElem {
	public function __construct() {
		parent::__construct("field_text",  __("Textbox", TLBM_TEXT_DOMAIN) );

		$this->description = __("Simple text field", TLBM_TEXT_DOMAIN);
	}

    /**
     * @param      $data_obj
     * @param null $insert_child
     *
     * @return mixed
     */
	public function GetFrontendOutput($data_obj, $insert_child = null) {
		return InputGenerator::GetFormControl("text", $data_obj->title, $data_obj->name, $data_obj->required == "yes");
	}
}

