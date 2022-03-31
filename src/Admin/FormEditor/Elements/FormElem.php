<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Admin\FormEditor\ItemSettingsElements\ElementSetting;
use TLBM\Admin\FormEditor\ItemSettingsElements\Input;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\MainFactory;

abstract class FormElem
{

    /**
     * @var string
     */
    public string $title;

    /**
     * @var string
     */
    public string $uniqueName;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var ElementSetting[]
     */
    public array $settings = [];

    /**
     * @var string
     */
    public string $menuCategory = "General";

    /**
     * @var string
     */
    public string $description;

    /**
     * @var bool
     */
    public bool $onlyInRoot = false;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct($name, $title)
    {
        $this->localization = MainFactory::get(LocalizationInterface::class);
        $this->title        = $title;
        $this->uniqueName   = $name;
        $this->menuCategory = $this->localization->getText("General", TLBM_TEXT_DOMAIN);
        $this->description  = "";
        $this->type         = $name;

        $settingCssClass = new Input("css_classes", $this->localization->getText("Custom CSS Classes (seperate with whitespace)", TLBM_TEXT_DOMAIN), "text", "", false, false, [], $this->localization->getText("Advanced", TLBM_TEXT_DOMAIN));
        $this->addSettings($settingCssClass);
    }

    public function addSettings(ElementSetting ...$settings)
    {
        $this->settings = array_merge($settings, $this->settings);
    }

    /**
     * @param string $name
     *
     * @return false|ElementSetting
     */
    public function getSettingsType(string $name)
    {
        foreach ($this->settings as $setting) {
            if ($setting->name == $name) {
                return $setting;
            }
        }

        return false;
    }
}