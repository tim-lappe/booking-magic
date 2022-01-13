<?php


namespace TLBM\Admin\FormEditor\Elements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use TLBM\Admin\FormEditor\ItemSettingsElements\Input;

final class SpacingElem extends FormElem {

	public function __construct() {
		parent::__construct( "spacing", __("Spacing", TLBM_TEXT_DOMAIN) );
        $this->description = __("Useful to leave space within the form", TLBM_TEXT_DOMAIN);

		$this->settings[] = new Input("spacing", __("Spacing (in px)", TLBM_TEXT_DOMAIN), "number",100);
		$this->menu_category = __("Layout", TLBM_TEXT_DOMAIN);
	}

    /**
     * @param      $data_obj
     * @param callable|null $insert_child
     *
     * @return mixed
     */
	public function GetFrontendOutput($data_obj, callable $insert_child = null): string {
		return "<div class=''></div>";
	}
}

