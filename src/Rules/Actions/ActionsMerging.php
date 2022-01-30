<?php

namespace TLBM\Rules\Actions;

use TLBM\Entity\Rule;
use TLBM\Entity\RuleAction;
use TLBM\Rules\Actions\Merging\Merger\Merger;
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
        $allSums = array();
        $this->getRuleActions(function (int $tstamp, array $actions) use (&$allSums) {

            /**
             * @var Merger[] $actionMergeChains
             */
            $actionMergeChains = array();

            /**
             * @var RuleAction $ruleAction
             */
            foreach ($actions as $ruleAction) {
                $handler = $this->ruleActionsManager->getActionHandler($ruleAction);
                if ($handler) {
                    $mergeTerm = $handler->getMergeTerm();
                    $nextMerger = $actionMergeChains[$mergeTerm] ?? null;
                    $actionMergeChains[$mergeTerm] = $handler->getMerger($nextMerger);
                }
            }

            $allSums[$tstamp] = array();
            foreach ($actionMergeChains as $term => $mergeChain) {
                $allSums[$tstamp][$term] = $mergeChain->merge();
            }
        });

        return $allSums;
    }

    /**
     * @param callable|null $forEach
     *
     * @return array
     */
    public function getRuleActions(callable $forEach = null): array
    {
        $result = $this->rulesQuery->getResult();

        $endResult = array();
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

            $endResult[$tstamp] = $actions;
        }

        return $endResult;
    }
}