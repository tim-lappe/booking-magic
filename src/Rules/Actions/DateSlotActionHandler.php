<?php

namespace TLBM\Rules\Actions;

use TLBM\Entity\RuleAction;
use TLBM\Rules\Actions\ActionData\ActionData;
use TLBM\Rules\Actions\ActionData\CapacityActionData;
use TLBM\Rules\Actions\Contracts\ActionHandlerInterface;
use TLBM\Rules\Actions\Merging\Merger\DateCapacityMerger;
use TLBM\Rules\Actions\Merging\Merger\Merger;

class DateSlotActionHandler implements ActionHandlerInterface
{

    /**
     * @var RuleAction
     */
    private RuleAction $ruleAction;

    /**
     * @param Merger|null $nextMerger
     *
     * @return DateCapacityMerger
     */
    public function getMerger(?Merger $nextMerger): DateCapacityMerger
    {
        return new DateCapacityMerger($this->getActionData(), $nextMerger);
    }

    /**
     * @return array
     */
    public function getMergeTerm(): array
    {
        return ["dayCapacity"
        ];
    }

    /**
     * @return RuleAction
     */
    public function getRuleAction(): RuleAction
    {
        return $this->ruleAction;
    }

    /**
     * @param RuleAction $ruleAction
     *
     * @return void
     */
    public function setRuleAction(RuleAction $ruleAction): void
    {
        $this->ruleAction = $ruleAction;
    }

    /**
     * @return ActionData|null
     */
    public function getActionData(): ?ActionData
    {
        return new CapacityActionData($this->getRuleAction()->getData());
    }
}