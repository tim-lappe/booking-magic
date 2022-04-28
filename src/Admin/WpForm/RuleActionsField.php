<?php


namespace TLBM\Admin\WpForm;

use TLBM\Admin\RuleActionsEditor\Contracts\RuleActionsEditorCollectionInterface;
use TLBM\Admin\WpForm\Contracts\FormFieldReadVarsInterface;
use TLBM\Entity\RuleAction;
use TLBM\MainFactory;

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
        $actionsCollection = MainFactory::get(RuleActionsEditorCollectionInterface::class);

        /**
         * @var RuleAction[] $actions
         */
        $actions = $value;
        if ( !is_array($actions)) {
            $actions = [];
        }
        ?>
        <tr>
            <th scope="row">
                <label for="<?php echo $this->escaping->escAttr($this->name) ?>">
                    <?php echo $this->escaping->escHtml($this->title); ?>
                </label>
            </th>
            <td>
                <div
                        data-value="<?php echo $this->escaping->escAttr(urlencode(json_encode($actions))); ?>"
                        data-actions="<?php echo $this->escaping->escAttr(urlencode(json_encode($actionsCollection->getRegisteredRuleActions()))); ?>"
                        data-name="<?php echo $this->escaping->escAttr($this->name) ?>"
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
                    $ruleAction->setData((array) $action_obj->actions);
                    $ruleAction->setActionType($action_obj->action_type);
                    $ruleAction->setWeekdays($action_obj->weekdays);

                    if (isset($action_obj->time_hour) && isset($action_obj->time_min)) {
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