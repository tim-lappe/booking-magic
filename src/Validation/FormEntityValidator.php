<?php

namespace TLBM\Validation;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\Form;
use TLBM\MainFactory;

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
        $localization = MainFactory::get(LocalizationInterface::class);
        if(empty($this->form->getTitle())) {
            $errors[] = $localization->getText("The title is too short", TLBM_TEXT_DOMAIN);
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