<?php


namespace TLBM\Admin\WpForm;

use TLBM\Entity\RuleAction;

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
                <div data-json="<?php echo urlencode(json_encode($actions)) ?>" data-name="<?php echo $this->name ?>" class="tlbm-actions tlbm-rule-actions-field"></div>
            </td>
        </tr>
        <?php
    }

    /**
     * @param $vars
     * @return RuleAction[]
     */
    public function ReadFromVars($vars): array {
        if(isset($vars[$this->name])) {
            $decoded_var = urldecode($vars[$this->name]);
            $json = json_decode($decoded_var);
            $actions = array();

            if(is_array($json)) {
                foreach ($json as $key => $action_obj) {
                    $ruleAction = new RuleAction();
                    $ruleAction->SetPriority(sizeof($json) - $key);
                    $ruleAction->SetActions(json_encode($action_obj->actions));
                    $ruleAction->SetActionType($action_obj->action_type);
                    $ruleAction->SetWeekdays($action_obj->weekdays);

                    if($action_obj->time_hour !== null && $action_obj->time_min !== null) {
                        $ruleAction->SetTimeHour($action_obj->time_hour);
                        $ruleAction->SetTimeMin($action_obj->time_min);
                    }

                    if ($action_obj->id > 0 && is_numeric($action_obj->id)) {
                        $ruleAction->SetId($action_obj->id);
                    }

                    $actions[] = $ruleAction;
                }
            }
            return $actions;
        }
        return array();
    }
}