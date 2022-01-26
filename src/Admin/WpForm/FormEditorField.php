<?php

namespace TLBM\Admin\WpForm;

use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;

class FormEditorField extends FormFieldBase
{

    private FormElementsCollectionInterface $elementsCollection;

    /**
     * @param FormElementsCollectionInterface $elementsCollection
     * @param string $name
     * @param string $title
     * @param $value
     */
    public function __construct(
        FormElementsCollectionInterface $elementsCollection,
        string $name,
        string $title,
        $value = ""
    ) {
        $this->elementsCollection = $elementsCollection;

        parent::__construct($name, $title, $value);
    }

    public function displayContent(): void
    {
        $form_data = $this->value;
        ?>

        <div class="tlbm-form-editor-field" data-name="<?php
        echo $this->name ?>" data-fields="<?php
        echo urlencode(json_encode($this->elementsCollection->getCategorizedFormElements())) ?>" data-json="<?php
        echo urlencode(json_encode($form_data)) ?>"></div>
        <?php
    }
}