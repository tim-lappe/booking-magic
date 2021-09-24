<?php


namespace TL_Booking\Admin\WpForm;


use TL_Booking\Admin\WpForm\RuleActionFields\DayMessageAction;
use TL_Booking\Admin\WpForm\RuleActionFields\DaySlotAction;
use TL_Booking\Admin\WpForm\RuleActionFields\RuleActionFieldBase;
use TL_Booking\Admin\WpForm\RuleActionFields\SetCapacityAction;
use TL_Booking\Admin\WpForm\RuleActionFields\TimeSlotAction;
use TL_Booking\Model\RuleActionCollection;

if (!defined('ABSPATH')) {
    return;
}

class RuleActionsField extends FormFieldBase {

    public function __construct( $name, $title, $value = "" ) {
        parent::__construct( $name, $title, $value );
    }

    function OutputHtml() {
        $fevalues = array();
        if($this->value instanceof RuleActionCollection) {
            $fevalues = json_encode($this->value->actions_list);
        }

        ?>
        <tr>
            <th scope="row"><label for="<?php echo $this->name ?>"><?php echo $this->title ?></label></th>
            <td>
                <div class="tlbm-actions tlbm-rule-actions-field">
                    <div class="tlbm-actions-list">

                    </div>
                    <select class="tlbm-action-select-type">
                        <?php foreach (self::GetRuleActionFields() as $ruleActionField): ?>
                            <option value="<?php echo $ruleActionField->key ?>"><?php echo $ruleActionField->title ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="button tlbm-add-action"><?php echo __("Add", TLBM_TEXT_DOMAIN) ?></button>
                    <input type="hidden" class="tlbm-action-select-data" name="<?php echo $this->name ?>" value="<?php echo str_replace("\"", "&quot;", $fevalues) ?>">
                </div>
            </td>
        </tr>
        <?php
    }

    /**
     * @return RuleActionFieldBase[]
     */
    private static function GetRuleActionFields(): array {
        return array(
            new DaySlotAction(),
            new TimeSlotAction(),
            new DayMessageAction()
        );
    }

    public static function InsertRuleActionFields($handle) {
        wp_localize_script($handle, "tlbm_rule_action_fields", self::GetRuleActionFields());
    }
}