<?php


namespace TLBM\Admin\WpForm;

use TLBM\Admin\WpForm\Contracts\FormBuilderInterface;
use TLBM\Admin\WpForm\Contracts\FormFieldReadVarsInterface;

if ( ! defined('ABSPATH')) {
    return;
}

class FormBuilder implements FormBuilderInterface
{

    /**
     * Array of all Form Fields
     *
     * @var FormFieldBase[]
     */
    private array $formFields = array();


    /**
     * @param string $name
     * @param mixed $vars
     *
     * @return mixed
     */
    public function readVars(string $name, $vars) {
        $formField = $this->getFormField($name);
        if($formField instanceof FormFieldReadVarsInterface) {
            return $formField->readFromVars($name, $vars);
        }

        return null;
    }

    /**
     * Prints the Head of The Form
     *
     */
    public function displayFormHead()
    {
        ?>
        <table class="form-table">
        <?php
    }

    /**
     * Prints the Footer of the Form
     */
    public function displayFormFooter()
    {
        ?>
        </table>
        <?php
    }

    /**
     * Returns an Array of Form Names
     *
     * @return array
     */
    public function getFormNames(): array
    {
        $form_names = array();
        foreach ($this->formFields as $form_field) {
            $form_names[] = $form_field->name;
        }

        return $form_names;
    }

    /**
     * @param string $name
     *
     * @return ?FormFieldBase
     */
    public function getFormField(string $name): ?FormFieldBase {
        foreach ($this->formFields as $formField) {
            if($formField->name == $name) {
                return $formField;
            }
        }

        return null;
    }

    /**
     * Adds a Form Field Object to the Form
     *
     * @param FormFieldBase $form_field
     */
    public function displayFormField(FormFieldBase $form_field)
    {
        $this->formFields[] = $form_field;

        $form_field->displayContent();
    }

    /**
     * @return void
     */
    public function init(): void
    {
        $this->formFields = array();
    }
}