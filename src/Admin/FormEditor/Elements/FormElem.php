<?php


namespace TLBM\Admin\FormEditor\Elements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use phpDocumentor\Reflection\Types\This;
use TLBM\Admin\FormEditor\ItemSettingsElements\ElementSetting;
use TLBM\Admin\FormEditor\ItemSettingsElements\Input;
use TLBM\Admin\FormEditor\ItemSettingsElements\Textarea;

abstract class FormElem {

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
	public array $settings = array();

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

	public function __construct($name, $title) {
		$this->title = $title;
		$this->unique_name = $name;
		$this->menu_category = __("General", TLBM_TEXT_DOMAIN);
		$this->description = "";
        $this->type = $name;

        $setting_css_class = new Input(
            "css_classes",
            __("Custom CSS Classes (seperate with whitespace)"),
            "text",
            "",
            false,
            false,
            array(),
            __("Advanced", TLBM_TEXT_DOMAIN)
        );

        $this->AddSettings($setting_css_class);
	}

    public function AddSettings(ElementSetting ...$settings) {
        $this->settings = array_merge($settings, $this->settings);
    }

	/**
	 * @param $name
	 *
	 * @return false|ElementSetting
	 */
	public function GetSettingsType($name)  {
		foreach($this->settings as $setting) {
			if($setting->name == $name) {
				return $setting;
			}
		}

		return false;
	}

	/**
	 * @param object $form_node
	 * @param callable|null $insert_child
	 *
	 * @return mixed
	 */
	public abstract function GetFrontendOutput(object $form_node, ?callable $insert_child = null);

    /**
     * @param $form_data
     * @param $input_vars
     *
     * @return bool
     */
	public function Validate($form_data, $input_vars): bool {
	    return true;
    }
}