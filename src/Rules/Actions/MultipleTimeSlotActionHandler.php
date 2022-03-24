<?php

namespace TLBM\Rules\Actions;

use TLBM\Entity\RuleAction;
use TLBM\Rules\Actions\ActionData\ActionData;
use TLBM\Rules\Actions\ActionData\MultipleTimeSlotData;
use TLBM\Rules\Actions\Merging\Merger\Merger;
use TLBM\Rules\Actions\Merging\Merger\MultipleTimeCapacityMerger;

class MultipleTimeSlotActionHandler implements Contracts\ActionHandlerInterface
{
    private RuleAction $ruleAction;

    /**
     * @param Merger|null $nextMerger
     *
     * @return Merger|null
     */
    public function getMerger(?Merger $nextMerger): ?Merger
    {
        return new MultipleTimeCapacityMerger($this->getActionData(), $nextMerger);
    }

    /**
     * @inheritDoc
     */
    public function getActionData(): ?ActionData
    {
        return new MultipleTimeSlotData($this->getRuleAction()->getData());
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
     * @return array
     */
    public function getMergeTerm(): array
    {
        return ["timeCapacities"
        ];
    }
}