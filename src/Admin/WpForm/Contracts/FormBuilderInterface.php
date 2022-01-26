<?php

namespace TLBM\Admin\WpForm\Contracts;

use TLBM\Admin\WpForm\FormFieldBase;

interface FormBuilderInterface
{
    /**
     * @param string $name
     * @param mixed $vars
     *
     * @return mixed
     */
    public function readVars(string $name, $vars);

    /**
     * @return void
     */
    public function init(): void;

    /**
     * Prints the Head of The Form
     *
     */
    public function displayFormHead();

    /**
     * Prints the Footer of the Form
     */
    public function displayFormFooter();

    /**
     * Returns an Array of Form Names
     *
     * @return array
     */
    public function getFormNames(): array;

    /**
     * @param string $name
     *
     * @return ?FormFieldBase
     */
    public function getFormField(string $name): ?FormFieldBase;

    /**
     * Adds a Form Field Object to the Form
     *
     * @param FormFieldBase $form_field
     */
    public function displayFormField(FormFieldBase $form_field);
}