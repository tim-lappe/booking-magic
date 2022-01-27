<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}


use TLBM\Admin\FormEditor\ItemSettingsElements\Input;
use TLBM\Admin\FormEditor\ItemSettingsElements\Select;

abstract class FormInputElem extends FormElem
{
    public function __construct($name, $title)
    {
        parent::__construct($name, $title);


        $setting_title = new Input(
            "title", __("Title", TLBM_TEXT_DOMAIN), "text", $this->title
        );

        $setting_name = new Input(
            "name", __("Name", TLBM_TEXT_DOMAIN), "text", str_replace(" ", "_", strtolower($this->title)), false, true, Input::GetForbiddenNameValues()
        );

        $setting_required = new Select(
            "required", __("Required", TLBM_TEXT_DOMAIN), array(
                          "yes" => __("Yes", TLBM_TEXT_DOMAIN),
                          "no"  => __("No", TLBM_TEXT_DOMAIN)
                      ), "no"
        );


        $this->AddSettings($setting_title, $setting_name, $setting_required);
        $this->has_user_input = true;
    }

    public function Validate($form_data, $input_vars): bool
    {
        if (isset($form_data['name'])) {
            $name = $form_data['name'];
            if (isset($input_vars[$name])) {
                return !empty($input_vars[$name]);
            }
        }

        return false;
    }
}