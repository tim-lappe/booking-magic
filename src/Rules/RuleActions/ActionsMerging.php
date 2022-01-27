<?php

namespace TLBM\Rules\RuleActions;

use TLBM\Entity\Rule;
use TLBM\Entity\RuleAction;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;
use TLBM\Rules\Contracts\RulesQueryInterface;
use TLBM\Rules\RulesQuery;

class ActionsMerging
{

    /**
     * @var RulesQuery
     */
    private RulesQueryInterface $rulesQuery;

    /**
     * @var RuleActionsManagerInterface
     */
    private RuleActionsManagerInterface $ruleActionsManager;

    /**
     * @param RuleActionsManagerInterface $ruleActionsManager
     * @param RulesQuery $query
     */
    public function __construct(RuleActionsManagerInterface $ruleActionsManager, RulesQueryInterface $query)
    {
        $this->rulesQuery         = $query;
        $this->ruleActionsManager = $ruleActionsManager;
    }

    /**
     * @return array
     */
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
                    if ( !isset($action_sum[$merge_term])) {
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
        $result = $this->rulesQuery->getResult();

        $end_result = array();
        foreach ($result as $tstamp => $rules) {
            $actions = array();

            /**
             * @var Rule $rule
             */
            foreach ($rules as $rule) {
                $actions = array_merge($actions, $rule->getActions()->toArray());
            }

            if ($forEach) {
                $forEach($tstamp, $actions);
            }

            $end_result[$tstamp] = $actions;
        }

        return $end_result;
    }
}