<?php


namespace TLBM\Admin\WpForm;

use TLBM\Admin\WpForm\Contracts\FormFieldReadVarsInterface;
use TLBM\Entity\RuleAction;

if ( !defined('ABSPATH')) {
    return;
}


class RuleActionsField extends FormFieldBase implements FormFieldReadVarsInterface
{

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function displayContent($value): void
    {
        /**
         * @var RuleAction[] $actions
         */
        $actions = $value;
        if ( !is_array($actions)) {
            $actions = array();
        }
        ?>
        <tr>
            <th scope="row">
                <label for="<?php
                echo $this->name ?>">
                    <?php
                    echo $this->title ?>
                </label>
            </th>
            <td>
                <div
                        data-json="<?php
                        echo urlencode(json_encode($actions)) ?>"
                        data-name="<?php
                        echo $this->name ?>"
                        class="tlbm-actions tlbm-rule-actions-field">

                </div>
            </td>
        </tr>
        <?php
    }


    /**
     * @param string $name
     * @param mixed $vars
     *
     * @return RuleAction[]
     */
    public function readFromVars(string $name, $vars): array
    {
        if (isset($vars[$name])) {
            $decoded_var = urldecode($vars[$name]);
            $json        = json_decode($decoded_var);
            $actions     = array();

            if (is_array($json)) {
                foreach ($json as $key => $action_obj) {
                    $ruleAction = new RuleAction();
                    $ruleAction->setPriority($key);
                    $ruleAction->setActions((array) $action_obj->actions);
                    $ruleAction->setActionType($action_obj->action_type);
                    $ruleAction->setWeekdays($action_obj->weekdays);

                    if ($action_obj->time_hour !== null && $action_obj->time_min !== null) {
                        $ruleAction->setTimeHour($action_obj->time_hour);
                        $ruleAction->setTimeMin($action_obj->time_min);
                    }

                    if ($action_obj->id > 0 && is_numeric($action_obj->id)) {
                        $ruleAction->setId($action_obj->id);
                    }

                    $actions[] = $ruleAction;
                }
            }

            return $actions;
        }

        return array();
    }
}