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
     * @param FormFieldBase $formField
     *
     * @return mixed
     */
    public function defineFormField(FormFieldBase $formField);

    /**
     * Adds a Form Field Object to the Form
     *
     * @param string $name
     * @param mixed $value
     */
    public function displayFormField(string $name, $value);
}