<?php


namespace TLBM\Admin\FormEditor\FrontendGeneration;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TLBM\Admin\FormEditor\Elements\FormElem;
use TLBM\Admin\FormEditor\ElementsCollection;

class FormFrontendGenerator {

	public object $form_node_tree;

	public function __construct($form_node_tree) {
		$this->form_node_tree = $form_node_tree;
	}

	/**
	 * @return string
	 */
	public function GenerateHtml(): string {
		$html = "<div class='tlbm-frontend-form'>";

		if(is_array($this->form_node_tree->children)) {
			$html .= $this->RecursiveHtmlGenerator($this->form_node_tree);
		}

		$html .= "</div>";

		return $html;
	}

	private function RecursiveHtmlGenerator(object $form_node): string {
		$html = "";

        $children = $form_node->children;
        $formData = $form_node->formData ?? null;

        if($formData && $formData->unique_name) {
            $registeredelem = ElementsCollection::GetElemByUniqueName($formData->unique_name);
            if ($registeredelem) {
                if (count($children) == 0) {
                    $html .= $registeredelem->GetFrontendOutput($form_node);
                } else {
                    $html .= $registeredelem->GetFrontendOutput($form_node, function ($child_node) {
                        return $this->RecursiveHtmlGenerator($child_node);
                    });
                }
            }
        } else {
            foreach ($children as $form_child_node) {
                $html .= $this->RecursiveHtmlGenerator($form_child_node);
            }
        }
		return $html;
	}
}