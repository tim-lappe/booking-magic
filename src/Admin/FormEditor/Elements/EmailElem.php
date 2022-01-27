<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}


use TLBM\Admin\FormEditor\FrontendGeneration\InputGenerator;

final class EmailElem extends FormInputElem
{
    public function __construct()
    {
        parent::__construct("field_email", __("E-Mail", TLBM_TEXT_DOMAIN));

        $this->description = __("A field in which the user can enter an e-mail", TLBM_TEXT_DOMAIN);

        $this->GetSettingsType("name")->default_value  = "email";
        $this->GetSettingsType("title")->default_value = __("E-Mail", TLBM_TEXT_DOMAIN);
    }

    /**
     * @param      $form_node
     * @param callable|null $insert_child
     *
     * @return mixed
     */
    public function getFrontendOutput($form_node, callable $insert_child = null)
    {
        return InputGenerator::GetFormControl(
            "email", $form_node->formData->title, $form_node->formData->name, $form_node->formData->required == "yes", ($form_node->formData->css_classes ?? "")
        );
    }
}

