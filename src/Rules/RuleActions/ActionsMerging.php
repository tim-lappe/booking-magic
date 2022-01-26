<?php

namespace TLBM\Rules\RuleActions;

use TLBM\Entity\Rule;
use TLBM\Entity\RuleAction;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;
use TLBM\Rules\RulesQuery;

class ActionsMerging
{

    /**
     * @var RulesQuery
     */
    private RulesQuery $rules_query;

    /**
     * @var RuleActionsManagerInterface
     */
    private RuleActionsManagerInterface $ruleActionsManager;

    public function __construct(RuleActionsManagerInterface $ruleActionsManager, RulesQuery $query)
    {
        $this->rules_query        = $query;
        $this->ruleActionsManager = $ruleActionsManager;
    }

    public function getRuleActionsMerged(): array
    {
        $all_sums = array();
        $this->getRuleActions(function (int $tstamp, array $actions) use (&$all_sums) {
            $action_sum = array();

            /**
             * @var RuleAction $rule_action
             */
            foreach ($actions as $rule_action) {
                $handler = $this->ruleActionsManager->getActionMerger($rule_action);
                if ($handler) {
                    $merge_term = $handler->getEmptyMergeInstance()->getMergeTerm();
                    if ( ! isset($action_sum[$merge_term])) {
                        $action_sum[$merge_term] = $handler->getEmptyMergeInstance();
                    }
                    $action_sum[$merge_term] = $handler->merge($action_sum[$merge_term]);
                }
            }

            $all_sums[$tstamp] = $action_sum;
        });

        return $all_sums;
    }

    /**
     * @param callable|null $forEach
     *
     * @return array
     */
    public function getRuleActions(callable $forEach = null): array
    {
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

            if ($forEach) {
                $forEach($tstamp, $actions);
            }

            $end_result[$tstamp] = $actions;
        }

        return $end_result;
    }
}