<?php


namespace TL_Booking\Admin\WpForm;


use TL_Booking\Model\PeriodCollection;

if (!defined('ABSPATH')) {
    return;
}

class PeriodEditorField extends FormFieldBase {

    public function __construct( $name, $title, $value = "" ) {
        parent::__construct( $name, $title, $value );
    }

    function OutputHtml() {
        $periodData = array();
        if($this->value instanceof PeriodCollection) {
            $periodData = json_encode($this->value->period_list);
        }
        ?>
        <tr>
            <th scope="row"><label for="<?php echo $this->name ?>"><?php echo $this->title ?></label></th>
            <td>
                <div class="tlbm-periods-picker">
                    <div class="tlbm-periods-rules-list">

                    </div>
                    <select class="tlbm-period-select-type">
                        <option value="date"><?php echo __("Date", TLBM_TEXT_DOMAIN) ?></option>
                        <option value="weekday"><?php echo __("Weekday", TLBM_TEXT_DOMAIN); ?></option>
                    </select>
                    <button class="button tlbm-add-period"><?php echo __("Add", TLBM_TEXT_DOMAIN) ?></button>
                    <input type="hidden" class="tlbm-period-select-data" name="<?php echo $this->name ?>" value="<?php echo str_replace("\"", "&quot;", $periodData) ?>">
                </div>
            </td>
        </tr>
        <?php
    }
}