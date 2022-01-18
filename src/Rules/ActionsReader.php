<?php

namespace TLBM\Rules;

use TLBM\Entity\Rule;
use TLBM\Entity\RuleAction;

class ActionsReader {

    private RulesQuery $rules_query;

    public function __construct(RulesQuery $query) {
        $this->rules_query = $query;
    }

    /**
     * @param callable|null $forEach
     * @return array
     */
    public function getRuleActions(callable $forEach = null): array {
        $result = $this->rules_query->getResult();

        $end_result = array();
        foreach ($result as $tstamp => $rules) {
            $actions = array();

            /**
             * @var Rule $rule
             */
            foreach ($rules as $rule) {
                $actions = array_merge($actions, $rule->GetActions()->toArray());

            }

            if($forEach) {
                $forEach($tstamp, $actions);
            }

            $end_result[$tstamp] = $actions;
        }

        return $end_result;
    }

    public function getRuleActionsMerged(): array {
        $all_sums = array();
        $this->getRuleActions(function(int $tstamp, array $actions) use (&$all_sums) {
            $action_sum = array(
                "full_date" => array(
                    "capacity" => 0
                ),
                "time_parts" => array()
            );

            /**
             * @var RuleAction $rule_action
             */
            foreach ($actions as $rule_action) {
                $action_data = (object)$rule_action->GetActions();
                $action_type = $rule_action->GetActionType();

                if ($action_type == "date_slot") {
                    if (isset($action_data->mode) && isset($action_data->amount)) {
                        if($action_data->mode == "set") {
                            $action_sum['full_date']['capacity'] = intval($action_data->amount);

                        } else if($action_data->mode == "add") {
                            $action_sum['full_date']['capacity'] += intval($action_data->amount);

                        } else if($action_data->mode == "subtract") {
                            $action_sum['full_date']['capacity'] -= intval($action_data->amount);

                        }
                    }
                } else if($action_type == "message") {
                    if (isset($action_data->message)) {
                        $action_sum['full_date']['message'] = $action_data->message;
                    }
                }
            }

            $all_sums[$tstamp] = $action_sum;
        });

        return $all_sums;
    }
}