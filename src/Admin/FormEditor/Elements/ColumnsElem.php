<?php


namespace TLBM\Admin\FormEditor\Elements;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TLBM\Admin\FormEditor\ItemSettingsElements\Select;


class ColumnsElem extends FormElem {

	public int $columns = 0;

	public function __construct( $name, $columns ) {
		parent::__construct($name,  __($columns ." Columns", TLBM_TEXT_DOMAIN) );
		$this->menu_category = "Layout";
		$this->columns = $columns;
        $this->type = "columns";
        $this->only_in_root = $columns > 3;
		$this->description = sprintf(__("Adds a section in which form fields can be displayed in a %s-column layout", TLBM_TEXT_DOMAIN), $columns);

        for($i = 1; $i <= $columns; $i++) {
            $this->settings[] = new Select(
                "split_" . $i,
                sprintf(__("Size Column %s", TLBM_TEXT_DOMAIN), $i),
                array( "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6", "7" => "7", "8" => "8", "9" => "9", "10" => "10", "11" => "11", "12" => "12"),
                1,
                false,
                false,
                __("Column Sizes")
            );
        }
	}

    /**
     * @param      $data_obj
     * @param callable|null $insert_child
     *
     * @return mixed
     */
	public function GetFrontendOutput($data_obj, callable $insert_child = null): string {
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
