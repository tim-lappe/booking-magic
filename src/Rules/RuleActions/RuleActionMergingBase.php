<?php


namespace TLBM\Rules\RuleActions;

use TLBM\Entity\RuleAction;
use TLBM\Rules\RuleActions\MergeEntities\MergeEntityBase;

abstract class RuleActionMergingBase
{

    /**
     * @var RuleAction
     */
    public RuleAction $rule_action;

    /**
     * @var object
     */
    public object $action_data;

    /**
     * ActionHandlerBase constructor.
     *
     * @param RuleAction $rule_action
     */
    public function __construct(RuleAction $rule_action)
    {
        $this->rule_action = $rule_action;
        $this->action_data = (object)$rule_action->GetActions();
    }

    /**
     * @param MergeEntityBase $merge_obj
     *
     * @return MergeEntityBase
     */
    abstract public function merge(MergeEntityBase &$merge_obj): MergeEntityBase;

    /**
     * @return MergeEntityBase
     */
    abstract public function getEmptyMergeInstance(): MergeEntityBase;
}