<?php


namespace TLBM\Admin\FormEditor\FrontendGeneration;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;

class FormFrontendGenerator
{

    public object $form_node_tree;

    /**
     * @var FormElementsCollectionInterface
     */
    private FormElementsCollectionInterface $elementsCollection;

    public function __construct(FormElementsCollectionInterface $elementsCollection, $form_node_tree)
    {
        $this->form_node_tree     = $form_node_tree;
        $this->elementsCollection = $elementsCollection;
    }

    /**
     * @return string
     */
    public function generateContent(): string
    {
        $html = "<div class='tlbm-frontend-form'>";

        if (is_array($this->form_node_tree->children)) {
            $html .= $this->recursiveHtmlGenerator($this->form_node_tree);
        }

        $html .= "</div>";

        return $html;
    }

    /**
     * @param mixed $form_node
     *
     * @return string
     */
    private function recursiveHtmlGenerator($form_node): string
    {
        $html = "";

        $children = $form_node->children;
        $formData = $form_node->formData ?? null;

        if ($formData && $formData->unique_name) {
            $registeredelem = $this->elementsCollection->getElemByUniqueName($formData->unique_name);
            if ($registeredelem) {
                if (count($children) == 0) {
                    $html .= $registeredelem->getFrontendOutput($form_node);
                } else {
                    $html .= $registeredelem->getFrontendOutput($form_node, function ($child_node) {
                        return $this->recursiveHtmlGenerator($child_node);
                    });
                }
            }
        } else {
            foreach ($children as $form_child_node) {
                $html .= $this->recursiveHtmlGenerator($form_child_node);
            }
        }

        return $html;
    }
}