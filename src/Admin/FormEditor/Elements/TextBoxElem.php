<?php


namespace TLBM\Admin\FormEditor\Elements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TLBM\Admin\FormEditor\FrontendGeneration\InputGenerator;

final class TextBoxElem extends FormInputElem {
	public function __construct() {
		parent::__construct("field_text",  __("Textbox", TLBM_TEXT_DOMAIN) );

		$this->description = __("Simple text field", TLBM_TEXT_DOMAIN);
	}

    /**
     * @param      $data_obj
     * @param callable|null $insert_child
     *
     * @return string
     */
	public function GetFrontendOutput($data_obj, callable $insert_child = null): string {
		return InputGenerator::GetFormControl("text", $data_obj->title, $data_obj->name, $data_obj->required == "yes");
	}
}