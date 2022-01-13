<?php


namespace TLBM\Admin\FormEditor\Elements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use TLBM\Admin\FormEditor\ItemSettingsElements\ElementSetting;

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
	 * @param $data_obj
	 * @param callable|null $insert_child
	 *
	 * @return mixed
	 */
	public abstract function GetFrontendOutput($data_obj, ?callable $insert_child = null);

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