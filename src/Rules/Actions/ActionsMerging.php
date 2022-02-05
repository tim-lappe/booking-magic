<?php

namespace TLBM\Rules\Actions;

use Iterator;
use TLBM\Entity\RuleAction;
use TLBM\Repository\Query\Contracts\FullRuleActionQueryInterface;
use TLBM\Repository\Query\FullRuleActionQuery;
use TLBM\Rules\Actions\Merging\Merger\Merger;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;

class ActionsMerging
{

    /**
     * @var FullRuleActionQuery
     */
    private FullRuleActionQueryInterface $rulesQuery;

    /**
     * @var RuleActionsManagerInterface
     */
    private RuleActionsManagerInterface $ruleActionsManager;

    /**
     * @param RuleActionsManagerInterface $ruleActionsManager
     * @param FullRuleActionQuery $query
     */
    public function __construct(RuleActionsManagerInterface $ruleActionsManager, FullRuleActionQueryInterface $query)
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
        $timedRules = $this->rulesQuery->getTimedRulesResult();
        foreach ($timedRules as $timedRule) {
            yield $timedRule->getTimedActions();
        }
    }

    /**
     * @return FullRuleActionQuery
     */
    public function getRulesQuery()
    {
        return $this->rulesQuery;
    }

    /**
     * @param FullRuleActionQuery $rulesQuery
     */
    public function setRulesQuery(FullRuleActionQuery $rulesQuery): void
    {
        $this->rulesQuery = $rulesQuery;
    }
}