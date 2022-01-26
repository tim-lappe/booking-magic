<?php


namespace TLBM\Admin\WpForm;

use TLBM\Entity\RuleAction;

if ( ! defined('ABSPATH')) {
    return;
}


class RuleActionsField extends FormFieldBase
{

    /**
     * @param $name
     * @param $vars
     *
     * @return RuleAction[]
     */
    public static function ReadFromVars($name, $vars): array
    {
        if (isset($vars[$name])) {
            $decoded_var = urldecode($vars[$name]);
            $json        = json_decode($decoded_var);
            $actions     = array();

            if (is_array($json)) {
                foreach ($json as $key => $action_obj) {
                    $ruleAction = new RuleAction();
                    $ruleAction->SetPriority($key);
                    $ruleAction->SetActions((array)$action_obj->actions);
                    $ruleAction->SetActionType($action_obj->action_type);
                    $ruleAction->SetWeekdays($action_obj->weekdays);

                    if ($action_obj->time_hour !== null && $action_obj->time_min !== null) {
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

    public function displayContent(): void
    {
        /**
         * @var RuleAction[] $actions
         */
        $actions = $this->value;
        if ( ! is_array($actions)) {
            $actions = array();
        }

        ?>
        <tr>
            <th scope="row"><label for="<?php
                echo $this->name ?>"><?php
                    echo $this->title ?></label></th>
            <td>
                <div data-json="<?php
                echo urlencode(json_encode($actions)) ?>" data-name="<?php
                echo $this->name ?>" class="tlbm-actions tlbm-rule-actions-field"></div>
            </td>
        </tr>
        <?php
    }
}