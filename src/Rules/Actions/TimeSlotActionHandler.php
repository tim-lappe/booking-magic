<?php

namespace TLBM\Rules\Actions;

use TLBM\Entity\RuleAction;
use TLBM\Rules\Actions\ActionData\ActionData;
use TLBM\Rules\Actions\ActionData\CapacityActionData;
use TLBM\Rules\Actions\Merging\Merger\Merger;
use TLBM\Rules\Actions\Merging\Merger\TimeCapacityMerger;

class TimeSlotActionHandler implements Contracts\ActionHandlerInterface
{

    /**
     * @var RuleAction
     */
    private RuleAction $ruleAction;

    /**
     * @inheritDoc
     */
    public function getMerger(?Merger $nextMerger): ?Merger
    {
        return new TimeCapacityMerger($this->getRuleAction(), $nextMerger);
    }

    /**
     * @inheritDoc
     */
    public function getMergeTerm(): string
    {
        return "timeCapacities";
    }

    /**
     * @inheritDoc
     */
    public function getRuleAction(): RuleAction
    {
        return $this->ruleAction;
    }

    /**
     * @inheritDoc
     */
    public function setRuleAction(RuleAction $ruleAction): void
    {
        $this->ruleAction = $ruleAction;
    }

    /**
     * @inheritDoc
     */
    public function getActionData(): ?ActionData
    {
        return new CapacityActionData($this->getRuleAction()->getData());
    }
}