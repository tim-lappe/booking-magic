<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( ! defined('ABSPATH')) {
    return;
}


use TLBM\Admin\FormEditor\FrontendGeneration\InputGenerator;

final class FirstNameElem extends FormInputElem
{
    public function __construct()
    {
        parent::__construct("field_first_name", __("First Name", TLBM_TEXT_DOMAIN));

        $this->menu_category = __("Predefined fields", TLBM_TEXT_DOMAIN);
        $this->description = __("A field in which the user can enter his/her first name", TLBM_TEXT_DOMAIN);

        $name_setting                = $this->GetSettingsType("name");
        $name_setting->default_value = "first_name";
        $name_setting->readonly      = true;

        $title_setting                = $this->GetSettingsType("title");
        $title_setting->default_value = __("First Name", TLBM_TEXT_DOMAIN);

        $required                = $this->GetSettingsType("required");
        $required->default_value = "yes";
    }

    /**
     * @param      $form_node
     * @param callable|null $insert_child
     *
     * @return mixed
     */
    public function GetFrontendOutput($form_node, callable $insert_child = null)
    {
        return InputGenerator::GetFormControl(
            "text",
            $form_node->formData->title,
            $form_node->formData->name,
            $form_node->formData->required == "yes",
            ($form_node->formData->css_classes ?? "")
        );
    }
}
