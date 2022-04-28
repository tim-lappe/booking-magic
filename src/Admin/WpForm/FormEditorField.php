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
     */
    public function __construct(
        FormElementsCollectionInterface $elementsCollection,
        string $name,
        string $title
    ) {
        $this->elementsCollection = $elementsCollection;

        parent::__construct($name, $title);
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function displayContent($value): void
    {
        $form_data = $value;
        ?>

        <div class="tlbm-form-editor-field" data-name="<?php echo $this->escaping->escAttr($this->name) ?>" data-fields="<?php
        echo $this->escaping->escAttr(urlencode(json_encode($this->elementsCollection->getCategorizedFormElements()))); ?>" data-json="<?php
        echo $this->escaping->escAttr(urlencode(json_encode($form_data))) ?>"></div>
        <?php
    }
}