<?php


namespace TLBM\Admin\WpForm;


use TLBM\Admin\WpForm\RuleActionFields\DayMessageAction;
use TLBM\Admin\WpForm\RuleActionFields\DaySlotAction;
use TLBM\Admin\WpForm\RuleActionFields\RuleActionFieldBase;
use TLBM\Admin\WpForm\RuleActionFields\TimeSlotAction;
use TLBM\Entity\RuleAction;
use TLBM\Model\RuleActionCollection;

if (!defined('ABSPATH')) {
    return;
}


class RuleActionsField extends FormFieldBase {

    public function __construct( $name, $title, $value = "" ) {
        parent::__construct( $name, $title, $value );
    }

    function OutputHtml() {

        /**
         * @var RuleAction[] $actions
         */
        $actions = $this->value;
        if(!is_array($actions)) {
            $actions = array();
        }
        ?>
        <tr>
            <th scope="row"><label for="<?php echo $this->name ?>"><?php echo $this->title ?></label></th>
            <td>
                <div data-json="<?php echo urlencode(json_encode($actions)) ?>" class="tlbm-actions tlbm-rule-actions-field"></div>
            </td>
        </tr>
        <?php
    }

    /**
     * @param $name
     * @return RuleActionFieldBase|null
     */
    private static function GetRuleActionFieldByName($name): ?RuleActionFieldBase {
        $fields = self::GetRuleActionFields();
        foreach ($fields as $field) {
            if($field->key = $name) {
                return $field;
            }
        }

        return null;
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