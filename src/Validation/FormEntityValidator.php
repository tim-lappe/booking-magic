<?php

namespace TLBM\Validation;

use TLBM\Entity\Form;

class FormEntityValidator
{
    /**
     * @var Form
     */
    private Form $form;

    /**
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @return array
     */
    public function isTitleValid(): array
    {
        $errors = array();
        if(empty($this->form->getTitle())) {
            $errors[] = __("The title is too short", TLBM_TEXT_DOMAIN);
        }

        return $errors;
    }

    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        return array_merge(
            $this->isTitleValid()
        );
    }
}