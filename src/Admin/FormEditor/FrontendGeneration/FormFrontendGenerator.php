<?php


namespace TLBM\Admin\FormEditor\FrontendGeneration;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use InvalidArgumentException;
use TLBM\Admin\FormEditor\Formeditor;
use TLBM\Admin\FormEditor\FormElements\FormElem;
use TLBM\Admin\FormEditor\FormElementsCollection;

class FormFrontendGenerator {

	public $form_data;

	public function __construct($form_data) {
		$this->form_data = $form_data;
	}

	/**
	 * @return string
	 */
	public function GenerateHtml(): string {
		$html = "<div class='tlbm-frontend-form'>";

		if(is_array($this->form_data)) {
			$html .= $this->RecursiveHtmlGenerator($this->form_data);
		}

		$html .= "</div>";

		return $html;
	}

	private function RecursiveHtmlGenerator($form_elements): string {
		$html = "";
		foreach($form_elements as $formelem) {
			$registeredelem = FormElementsCollection::GetElemByUniqueName($formelem->unique_name);
			if($registeredelem instanceof FormElem) {
				if(!isset($formelem->children)) {
					$html .= $registeredelem->GetFrontendOutput( $formelem );
				} else if(is_array($formelem->children) && sizeof($formelem->children) > 0) {
					$html .= $registeredelem->GetFrontendOutput( $formelem , function ($childnum) use ($formelem) {
						$subhtml = "";
						$children = $formelem->children[$childnum];
						if(isset($children) && is_array($children)) {
							$subhtml .= $this->RecursiveHtmlGenerator( $children );
						}
						return $subhtml;
					});
				}
			}
		}
		return $html;
	}
}