<?php


namespace TLBM\Admin\FormEditor\FrontendGeneration;

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

    public static function GetSelectControle($title, $name, $key_value, $required): string {
        $required = $required ? "required" : "";
        $html = "<div class='tlbm-fe-form-control'>";
        $html .= "<label>";
        $html .= "<span class='tlbm-input-title'>".$title."</span>";
        $html .= "<select class='tlbm-input-field' name='".$name."' ".$required.">";

        foreach ($key_value as $key => $value) {
            $html .= "<option value='".$key."'>".$value."</option>";
        }

        $html .= "</select>";
        $html .= "</label>";
        $html .= "</div>";
        return $html;
    }
}