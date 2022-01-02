<?php


namespace TLBM\Admin\WpForm;

if (!defined('ABSPATH')) {
    return;
}

class PeriodEditorField extends FormFieldBase {

    public function __construct( $name, $title, $value = "" ) {
        parent::__construct( $name, $title, $value );
    }

    function OutputHtml() {
        $data = array();
        ?>
        <tr>
            <th scope="row"><label for="<?php echo $this->name ?>"><?php echo $this->title ?></label></th>
            <td>
                <div class="tlbm-period-select-field" data-name="<?php echo $this->name ?>" data-json="<?php echo urlencode(json_encode($data)) ?>"></div>
            </td>
        </tr>
        <?php
    }
}