<?php


namespace TLBM\Model;


use TLBM\Admin\FormEditor\FormElements\FormElem;

if (!defined('ABSPATH')) {
    return;
}

class FormElementsDataPack {

    /**
     * @var FormElem
     */
    public $form_element;

    /**
     * @var array
     */
    public $element_data;

    /**
     * @var array;
     */
    public $input_values;


    public function Validate(): bool {
        return $this->form_element->Validate($this->element_data, $this->input_values);
    }

    public function GetSettingValue($name): string {
        if(isset($this->element_data[$name])) {
            return $this->element_data[$name];
        } else {
            return "";
        }
    }

	public function GetInputValue($name): string {
		if(isset($this->input_values[$name])) {
			return $this->input_values[$name];
		} else {
			return "";
		}
	}
}