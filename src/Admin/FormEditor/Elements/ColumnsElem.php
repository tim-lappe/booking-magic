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

        $settings = array();
        for($i = 1; $i <= $columns; $i++) {
            $settings[] = new Select(
                "split_" . $i,
                sprintf(__("Size Column %s", TLBM_TEXT_DOMAIN), $i),
                array( "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6", "7" => "7", "8" => "8", "9" => "9", "10" => "10", "11" => "11", "12" => "12"),
                1,
                false,
                false,
                __("Column Sizes")
            );
        }

        $this->AddSettings(...$settings);
	}

    /**
     * @param object $form_node
     * @param callable|null $insert_child
     *
     * @return mixed
     */
	public function GetFrontendOutput(object $form_node, callable $insert_child = null): string {
		$html = "<div class='tlbm-fe-columns " . ($form_node->formData->css_classes ?? "") . "'>";

        foreach($form_node->children as $column_num => $column_node) {
            $fgrow = $form_node->formData->{"split_" . ($column_num + 1)};
            $html .= "<div class='tlbm-fe-column' style='flex-grow: " . $fgrow . "'>";

            foreach($column_node->children as $child_form_node) {
                if ($insert_child != null) {
                    $html .= $insert_child($child_form_node);
                }
            }

            $html .= "</div>";
        }

		$html .= "</div>";

		return $html;
	}
}
