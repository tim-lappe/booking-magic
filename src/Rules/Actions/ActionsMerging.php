<?php

namespace TLBM\Rules\Actions;

use Iterator;
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
     * @return TimedMergeData[]
     */
    public function getRuleActionsMerged(): array
    {
        $mergedCollection = [];
        foreach ($this->getRuleActions() as $timedActions) {

            /**
             * @var Merger[] $actionMergeChains
             */
            $actionMergeChains = array();

            /**
             * @var RuleAction $ruleAction
             */
            foreach ($timedActions->getRuleActions() as $ruleAction) {
                $handler = $this->ruleActionsManager->getActionHandler($ruleAction);
                if ($handler) {
                    $mergeTerm = $handler->getMergeTerm();
                    $nextMerger = $actionMergeChains[$mergeTerm] ?? null;
                    $actionMergeChains[$mergeTerm] = $handler->getMerger($nextMerger);
                }
            }

            $mergedActions = [];
            foreach ($actionMergeChains as $term => $mergeChain) {
                $mergedActions[$term] = $mergeChain->merge()->getMergeResult();
            }

            $mergedCollection[] = new TimedMergeData($timedActions->getDateTime(), $mergedActions);
        }

        return $mergedCollection;
    }

    /**
     *
     * @return Iterator
     */
    public function getRuleActions(): Iterator
    {
        $timedRules = $this->rulesQuery->getResult();
        foreach ($timedRules as $timedRule) {
            yield $timedRule->getTimedActions();
        }
    }
}