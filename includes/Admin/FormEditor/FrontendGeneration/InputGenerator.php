<?php


namespace TL_Booking\Admin\FormEditor\FrontendGeneration;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


class InputGenerator {

	public static function GetFormControl($type, $title, $name, $required): string {
		$required = $required ? "required" : "";
		$html = "<div class='tlbm-fe-form-control'>";
		$html .= "<label>";
		$html .= "<span class='tlbm-input-title'>".$title."</span>";
		$html .= "<input class='tlbm-input-field' type='".$type."' name='".$name."' ".$required.">";
		$html .= "</label>";
		$html .= "</div>";
		return $html;
	}
}