<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}


use TLBM\Admin\FormEditor\Contracts\FrontendElementInterface;
use TLBM\Admin\FormEditor\FormInputGenerator;
use TLBM\Admin\FormEditor\ItemSettingsElements\Input;
use TLBM\Admin\FormEditor\ItemSettingsElements\Select;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\Admin\FormEditor\Validators\FormElementValidatorInterface;

abstract class FormInputElem extends FormElem implements FormElementValidatorInterface, FrontendElementInterface
{
    /**
     * @param string $name
     * @param string $title
     */
    public function __construct(string $name, string $title)
    {
        parent::__construct($name, $title);

        $settingTitle = new Input("title", __("Title", TLBM_TEXT_DOMAIN), "text", $this->title);
        $settingName = new Input("name", __("Name", TLBM_TEXT_DOMAIN), "text", str_replace(" ", "_", strtolower($this->title)), false, true, Input::GetForbiddenNameValues());
        $settingRequired = new Select("required", __("Required", TLBM_TEXT_DOMAIN), [
                "yes" => __("Yes", TLBM_TEXT_DOMAIN),
                "no"  => __("No", TLBM_TEXT_DOMAIN)
            ],  "yes"
        );

        $this->addSettings($settingTitle, $settingName, $settingRequired);
        $this->has_user_input = true;
    }

    /**
     * @param LinkedFormData $linkedFormData
     *
     * @return bool
     */
    public function validate(LinkedFormData $linkedFormData): bool
    {
        $linkedSettings = $linkedFormData->getLinkedSettings();
        if($linkedSettings->getValue("required") == "no") {
            return true;
        }

        $name = $linkedSettings->getValue("name");
        $value = $linkedFormData->getInputVarByName($name);
        return !empty($value) && $value != "null";
    }

    /**
     * @param LinkedFormData $linkedFormData
     * @param callable|null $displayChildren
     *
     * @return string
     */
    public function getFrontendContent(LinkedFormData $linkedFormData, callable $displayChildren = null): string
    {
        $generator = new FormInputGenerator($linkedFormData);
        return $generator->getFormControl();
    }
}