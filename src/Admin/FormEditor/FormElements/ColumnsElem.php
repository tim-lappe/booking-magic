<?php


namespace TLBM\Admin\FormEditor\FormElements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use TLBM\Admin\FormEditor\FormItemSettingsElements\Select;
use TLBM\Admin\FormEditor\FormItemSettingsElements\SettingsPrinting;
use TLBM\Admin\FormEditor\FormItemSettingsElements\SettingsStyleEdit;

class ColumnsElem extends FormElem {

	public $columns = 0;

	public function __construct( $name, $columns ) {
		parent::__construct($name,  __($columns ." Columns", TLBM_TEXT_DOMAIN) );
		$this->menu_category = "Layout";
		$this->columns = $columns;

		$this->description = sprintf(__("Adds a section in which form fields can be displayed in a %s-column layout", TLBM_TEXT_DOMAIN), $columns);

		$this->SetEditorOutput($columns);
	}

	public function SetEditorOutput($columns) {
		$this->editor_output = "<div class='tlbm-form-item-columns'>";

		for($i = 1; $i <= $columns; $i++) {
			$this->editor_output .= "<div class='tlbm-form-column tlbm-form-column-number-".$i." tlbm-draggable-container tlbm-form-dragdrop-container'></div>";

			$this->settings[] = new Select("split_" . $i, "Size Column " . $i, array( "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6", "7" => "7", "8" => "8", "9" => "9", "10" => "10", "11" => "11", "12" => "12"),
				new SettingsPrinting("", array(
					new SettingsStyleEdit("flex-grow", ".tlbm-form-column-number-".$i)
				)),
				1
			);
		}

		$this->editor_output .= "</div>";
	}

    /**
     * @param      $data_obj
     * @param null $insert_child
     *
     * @return mixed
     */
	public function GetFrontendOutput($data_obj, $insert_child = null): string {
		$html = "<div class='tlbm-fe-columns'>";

		for($i = 0; $i < $this->columns; $i++) {
			$fgrow = $data_obj->{"split_" . ($i + 1)};
			$html .= "<div class='tlbm-fe-column' style='flex-grow: ".$fgrow."'>";

			if($insert_child != null) {
                $html .= $insert_child($i);
            }

			$html .= "</div>";
		}

		$html .= "</div>";

		return $html;
	}
}
