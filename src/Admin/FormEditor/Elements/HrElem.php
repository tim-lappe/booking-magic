<?php


namespace TLBM\Admin\FormEditor\Elements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


final class HrElem extends FormElem {

	public function __construct() {
		parent::__construct( "hr", __("Horizontal Line", TLBM_TEXT_DOMAIN) );

		$this->description = __("Inserts a horizontal dividing line to visually separate areas from each other", TLBM_TEXT_DOMAIN);

		$this->editor_output = "<div class='tlbm-form-item-hr'><hr></div>";
		$this->menu_category = __("Layout", TLBM_TEXT_DOMAIN);
	}

    /**
     * @param      $form_node
     * @param callable|null $insert_child
     *
     * @return mixed
     */
	public function GetFrontendOutput($form_node, callable $insert_child = null): string {
		return "<hr>";
	}
}

