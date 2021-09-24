<?php


namespace TL_Booking\Admin\FormEditor\FormElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use TL_Booking\Admin\FormEditor\FormItemSettingsElements\Input;
use TL_Booking\Admin\FormEditor\FormItemSettingsElements\SettingsPrinting;
use TL_Booking\Admin\FormEditor\FormItemSettingsElements\SettingsStyleEdit;

final class SpacingElem extends FormElem {

	public function __construct() {
		parent::__construct( "spacing", __("Spacing", TLBM_TEXT_DOMAIN) );

		$this->editor_output = "<div class='tlbm-form-item-spacing'></div>";
		$this->description = __("Useful to leave space within the form", TLBM_TEXT_DOMAIN);

		$this->settings[] = new Input("spacing", __("Spacing (in px)", TLBM_TEXT_DOMAIN), "number", new SettingsPrinting("", array(new SettingsStyleEdit("height",".tlbm-form-item-spacing")), array("*" => "{x}px")), 100);
		$this->menu_category = __("Layout", TLBM_TEXT_DOMAIN);
	}

    /**
     * @param      $data_obj
     * @param null $insert_child
     *
     * @return mixed
     */
	public function GetFrontendOutput($data_obj, $insert_child = null): string {
		return "<div class=''></div>";
	}
}

