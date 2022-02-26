<?php


namespace TLBM\Admin\FormEditor\Elements;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Admin\FormEditor\ItemSettingsElements\ElementSetting;
use TLBM\Admin\FormEditor\ItemSettingsElements\Input;
use TLBM\CMS\Contracts\LocalizationInterface;
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
    public string $unique_name;

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
    public $menu_category = "General";

    /**
     * @var string
     */
    public string $description;

    /**
     * @var bool
     */
    public bool $only_in_root = false;

    /**
     * @var bool
     */
    public bool $has_user_input = false;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct($name, $title)
    {
        $this->localization = MainFactory::get(LocalizationInterface::class);
        $this->title         = $title;
        $this->unique_name   = $name;
        $this->menu_category = $this->localization->__("General", TLBM_TEXT_DOMAIN);
        $this->description   = "";
        $this->type          = $name;

        $setting_css_class = new Input("css_classes", $this->localization->__("Custom CSS Classes (seperate with whitespace)", TLBM_TEXT_DOMAIN), "text", "", false, false, [], $this->localization->__("Advanced", TLBM_TEXT_DOMAIN));

        $this->addSettings($setting_css_class);
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