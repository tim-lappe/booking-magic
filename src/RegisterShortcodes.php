<?php


namespace TLBM;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use TLBM\Output\FormPrint;
use TLBM\Request\RequestBase;

class RegisterShortcodes {
	public function __construct() {
		add_action("init", array($this, "AddShortcodes"));
	}

	public function AddShortcodes() {
		add_shortcode(TLBM_SHORTCODETAG_FORM, array($this, "FormShortcode"));
	}

	public function FormShortcode( $args ): string {
		$request = $GLOBALS['TLBM_REQUEST'];
		if(is_array($args) && sizeof($args) > 0) {
			if(isset($args['id'])) {
				if ( $request instanceof Request && $request->current_action != null && $request->current_action->html_output ) {
					return $request->current_action->GetHtmlOutput( $_REQUEST );
				} else {
					return FormPrint::PrintForm( $args['id'] );
				}
			}
		}

		return "";
	}
}