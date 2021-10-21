<?php


namespace TLBM\Output;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TLBM\Model\Form;
use TLBM\Form\FormManager;

class FormPrint {

	public static function PrintForm( $id ): string {
		$form = FormManager::GetForm($id);

		$html = FrontendMessenger::GetMessangesPrint();

		if($form instanceof Form) {
			$html .= "<form action='".$_SERVER['REQUEST_URI']."' method='post'>";
			$html .= $form->frontend_html;
			$html .= "<input type='hidden' name='form' value='" . $form->wp_post_id . "'>";

			if(get_option("single_page_booking") == "on") {
				$html .= "<input type='hidden' name='action' value='dobooking'>";
				$html .= wp_nonce_field("dobooking_action", "_wpnonce", true, false);
				$html .= "<button>" . __( "Book now", TLBM_TEXT_DOMAIN ) . "</button>";
			} else {
				$html .= "<input type='hidden' name='action' value='showbookingoverview'>";
				$html .= wp_nonce_field("showbookingoverview_action", "_wpnonce", true, false);
				$html .= "<button>" . __( "Continue", TLBM_TEXT_DOMAIN ) . "</button>";
			}

			$html .= "</form>";
			return $html;
		}
		return "";
	}
}