<?php

namespace TLBM\Rules\Actions;

use TLBM\Entity\RuleAction;
use TLBM\Rules\Actions\ActionData\ActionData;
use TLBM\Rules\Actions\ActionData\SlotOverwriteData;
use TLBM\Rules\Actions\Merging\Merger\Merger;
use TLBM\Rules\Actions\Merging\Merger\SlotOverwriteMerger;

class SlotOverwriteHandler implements Contracts\ActionHandlerInterface
{

    private RuleAction $ruleAction;

    /**
     * @inheritDoc
     */
    public function getMerger(?Merger $nextMerger): ?Merger
    {
        return new SlotOverwriteMerger($this->getActionData(), $nextMerger);
    }

    /**
     * @inheritDoc
     */
    public function getActionData(): ?ActionData
    {
        return new SlotOverwriteData($this->getRuleAction()->getData());
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
    public function getMergeTerm(): array
    {
        return ["dayCapacity",
            "timeCapacities"
        ];
    }
}