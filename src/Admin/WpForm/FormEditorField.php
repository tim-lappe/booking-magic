<?php

namespace TLBM\Admin\WpForm;


use TLBM\Admin\FormEditor\Formeditor;

class FormEditorField extends FormFieldBase {

    public function __construct( $name, $title, $value = "" ) {
        parent::__construct( $name, $title, $value );
    }

    function OutputHtml() {
        $form_data = $this->value;
        ?>

        <div class="tlbm-form-editor-field" data-name="<?php echo $this->name ?>" data-fields="<?php echo urlencode(json_encode(Formeditor::GetFormElements())) ?>" data-json="<?php echo urlencode(json_encode($form_data)) ?>"></div>
        <?php
    }
}