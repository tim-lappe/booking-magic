<?php


namespace TLBM\Rules\RuleActions;

use TLBM\Entity\RuleAction;
use TLBM\Rules\RuleActions\Contracts\RuleActionMergingInterface;
use TLBM\Rules\RuleActions\MergeEntities\Contracts\MergeEntityInterface;

abstract class RuleActionMergingBase implements RuleActionMergingInterface
{

    /**
     * @var RuleAction
     */
    public RuleAction $ruleAction;

    /**
     * @var object
     */
    public object $actionData;

    /**
     * ActionHandlerBase constructor.
     *
     * @param RuleAction $ruleAction
     */
    public function __construct(RuleAction $ruleAction)
    {
        $this->ruleAction = $ruleAction;
        $this->actionData = (object) $ruleAction->getActions();
    }

    /**
     * @param MergeEntityInterface $mergeObj
     *
     * @return MergeEntityInterface
     */
    abstract public function merge(MergeEntityInterface &$mergeObj): MergeEntityInterface;

    /**
     * @return MergeEntityInterface
     */
    abstract public function getEmptyMergeInstance(): MergeEntityInterface;
}