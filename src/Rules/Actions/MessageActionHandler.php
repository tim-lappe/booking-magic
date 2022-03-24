<?php

namespace TLBM\Rules\Actions;

use TLBM\Entity\RuleAction;
use TLBM\Rules\Actions\ActionData\ActionData;
use TLBM\Rules\Actions\ActionData\MessageData;
use TLBM\Rules\Actions\Contracts\ActionHandlerInterface;
use TLBM\Rules\Actions\Merging\Merger\Merger;
use TLBM\Rules\Actions\Merging\Merger\MessageMerger;

class MessageActionHandler implements ActionHandlerInterface
{

    /**
     * @var RuleAction
     */
    private RuleAction $ruleAction;

    /**
     * @param Merger|null $nextMerger
     *
     * @return Merger|null
     */
    public function getMerger(?Merger $nextMerger): ?Merger
    {
        return new MessageMerger($this->getActionData(), $nextMerger);
    }

    /**
     * @return array
     */
    public function getMergeTerm(): array
    {
        return ["messages"
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
        return new MessageData($this->getRuleAction()->getData());
    }
}