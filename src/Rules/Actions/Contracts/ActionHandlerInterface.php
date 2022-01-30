<?php

namespace TLBM\Rules\Actions\Contracts;

use TLBM\Entity\RuleAction;
use TLBM\Rules\Actions\ActionData\ActionData;
use TLBM\Rules\Actions\Merging\Merger\Merger;

interface ActionHandlerInterface
{
    /**
     * @param Merger|null $nextMerger
     *
     * @return ?Merger
     */
    public function getMerger(?Merger $nextMerger): ?Merger;

    /**
     * @return string
     */
    public function getMergeTerm(): string;

    /**
     * @return RuleAction
     */
    public function getRuleAction(): RuleAction;

    /**
     * @param RuleAction $ruleAction
     *
     * @return void
     */
    public function setRuleAction(RuleAction $ruleAction): void;

    /**
     * @return ?ActionData
     */
    public function getActionData(): ?ActionData;
}