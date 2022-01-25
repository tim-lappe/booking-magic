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
     * @param      $form_node
     * @param callable|null $insert_child
     *
     * @return string
     */
	public function GetFrontendOutput($form_node, callable $insert_child = null): string {
		return InputGenerator::GetFormControl("text", $form_node->formData->title, $form_node->formData->name, $form_node->formData->required == "yes", ($form_node->formData->css_classes ?? ""));
	}
}