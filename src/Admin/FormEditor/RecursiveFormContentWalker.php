<?php

namespace TLBM\Admin\FormEditor;

use TLBM\Admin\FormEditor\Contracts\AdminElementInterface;
use TLBM\Admin\FormEditor\Contracts\FrontendElementInterface;
use TLBM\Admin\FormEditor\Elements\FormElem;

class RecursiveFormContentWalker implements Contracts\RecursiveFormDataWalkerInterface
{
    /**
     * @var string
     */
    private string $content = "";

    /**
     * @var mixed
     */
    private $inputVars;

    /**
     * @param mixed $inputVars
     */
    public function __construct($inputVars = null)
    {
        $this->inputVars = $inputVars;
    }

    /**
     * @inheritDoc
     */
    public function walk(array $formNode, ?FormElem $element, array $children, callable $childCallback = null)
    {
        $html = "";
        if(is_admin() && ($element instanceof AdminElementInterface && $formNode != null)) {
            $linkedElement = new LinkedFormData($formNode, $element, $this->inputVars);
            $html .= $element->getAdminContent($linkedElement, $childCallback);

        } elseif ($element instanceof FrontendElementInterface && $formNode != null) {
            $linkedElement = new LinkedFormData($formNode, $element, $this->inputVars);
            $html .= $element->getFrontendContent($linkedElement, $childCallback);

        } else {
            foreach ($children as $child) {
                $html .= $childCallback($child);
            }
        }

        return $html;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getInputVars()
    {
        return $this->inputVars;
    }

    /**
     * @param mixed $inputVars
     */
    public function setInputVars($inputVars)
    {
        $this->inputVars = $inputVars;
    }
}