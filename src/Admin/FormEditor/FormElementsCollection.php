<?php

namespace TLBM\Admin\FormEditor;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;
use TLBM\Admin\FormEditor\Elements\FormElem;

class FormElementsCollection implements FormElementsCollectionInterface
{

    /**
     * @var FormElem[]
     */
    private array $formElements = array();

    /**
     * @param FormElem $formelem
     */
    public function registerFormElement(FormElem $formelem): void
    {
        $this->formElements[] = $formelem;
    }

    /**
     * @return FormElem[]
     */
    public function getRegisteredFormElements(): array
    {
        return $this->formElements;
    }

    public function getElemByUniqueName($uniqueName): ?FormElem
    {
        foreach ($this->formElements as $elem) {
            if ($elem->uniqueName == $uniqueName) {
                return $elem;
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getCategorizedFormElements(): array
    {
        $formelements_arr = array();
        foreach ($this->formElements as $elem) {
            $formelements_arr[] = get_object_vars($elem);
        }

        return $formelements_arr;
    }
}