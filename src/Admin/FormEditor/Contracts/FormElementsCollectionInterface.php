<?php

namespace TLBM\Admin\FormEditor\Contracts;

use TLBM\Admin\FormEditor\Elements\FormElem;

interface FormElementsCollectionInterface
{
    /**
     * @return void
     */
    public function registerFormElements(): void;

    /**
     * @return FormElem[]
     */
    public function getRegisteredFormElements(): array;

    /**
     * @param string $unique_name
     *
     * @return ?FormElem
     */
    public function getElemByUniqueName(string $unique_name): ?FormElem;

    /**
     * @return array
     */
    public function getCategorizedFormElements(): array;

    /**
     * @param FormElem $formelem
     */
    public function addFormElement(FormElem $formelem): void;
}