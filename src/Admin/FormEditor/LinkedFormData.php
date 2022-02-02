<?php

namespace TLBM\Admin\FormEditor;

use TLBM\Admin\FormEditor\Elements\FormElem;
use TLBM\Admin\FormEditor\Validators\FormElementValidatorInterface;

class LinkedFormData
{
    /**
     * @var mixed
     */
    private $formNode;

    /**
     * @var mixed
     */
    private $inputVars;

    /**
     * @var FormElem
     */
    private FormElem $formElement;

    /**
     * @var LinkedSettings
     */
    private LinkedSettings $linkedSettings;

    /**
     * @param mixed $formNode
     * @param FormElem $formElement
     * @param mixed $inputVars
     */
    public function __construct($formNode, FormElem $formElement, $inputVars)
    {
        $this->formNode    = $formNode;
        $this->formElement = $formElement;
        $this->inputVars = $inputVars;

        $this->linkedSettings = LinkedSettings::createFromData($this->formNode, $formElement->settings);
    }

    /**
     * @return bool
     */
    public function validateInput(): bool
    {
        $element = $this->getFormElement();
        if($element instanceof FormElementValidatorInterface) {
            return $element->validate($this);
        }

        return false;
    }

    /**
     * @return FormElem
     */
    public function getFormElement(): FormElem
    {
        return $this->formElement;
    }

    /**
     * @param FormElem $formElement
     */
    public function setFormElement(FormElem $formElement): void
    {
        $this->formElement = $formElement;
    }

    /**
     * @return mixed
     */
    public function getFormNode()
    {
        return $this->formNode;
    }

    /**
     * @param mixed $formNode
     */
    public function setFormNode($formNode): void
    {
        $this->formNode = $formNode;
    }

    /**
     * @return LinkedSettings
     */
    public function getLinkedSettings(): LinkedSettings
    {
        return $this->linkedSettings;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getInputVarByName(string $name): string
    {
        if($this->inputVars != null && isset($this->inputVars[$name])) {
            return trim($this->inputVars[$name]);
        }
        return "";
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
    public function setInputVars($inputVars): void
    {
        $this->inputVars = $inputVars;
    }
}