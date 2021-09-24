<?php


namespace TL_Booking\Admin\WpForm;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

class FormBuilder {

	/**
	 * Array of all Form Fields
	 *
	 * @var FormFieldBase[]
	 */
	private $form_fields = array();

	public function __construct() {

	}

    /**
     * Prints the Head of The Form
     *
     */
	public function PrintFormHead() {
		?>
        <table class="form-table">
		<?php
	}

	/**
	 * Prints the Footer of the Form
	 */
	public function PrintFormFooter() {
		?>
		</table>
		<?php
	}

	/**
	 * Returns an Array of Form Names
	 *
	 * @return array
	 */
	public function GetFormNames(): array {
		$form_names = array();
		foreach ($this->form_fields as $form_field) {
			$form_names[] = $form_field->name;
		}
		return $form_names;
	}

    /**
     * Adds a Form Field Object to the Form
     *
     * @param FormFieldBase $form_field
     */
	public function PrintFormField(FormFieldBase $form_field) {
		$this->form_fields[] = $form_field;

		$form_field->OutputHtml();
	}
}