<?php


namespace TLBM\Rules\Actions;


use TLBM\Entity\RuleAction;
use TLBM\Rules\Actions\Contracts\ActionHandlerInterface;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;

class RuleActionsManager implements RuleActionsManagerInterface
{

    /**
     * @var string[]
     */
    public array $ruleActionHandlers = array();


    /**
     * @param string $term
     * @param string $class
     *
     * @return bool
     */
    public function registerActionHandlerClass(string $term, string $class): bool
    {
        if(!isset($this->ruleActionHandlers[$term])) {
            $this->ruleActionHandlers[$term] = $class;
            return true;
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function getAllActionsHandlerClasses(): array {
        return $this->ruleActionHandlers;
    }

    /**
     *
     * @param RuleAction $action
     *
     * @return ?ActionHandlerInterface
     */
    public function getActionHandler(RuleAction $action): ?ActionHandlerInterface
    {
        if (isset($this->ruleActionHandlers[$action->getActionType()])) {
            $actionHandler = new $this->ruleActionHandlers[$action->getActionType()]();
            if($actionHandler instanceof ActionHandlerInterface) {
                $actionHandler->setRuleAction($action);
                return $actionHandler;
            }
        }

        return null;
    }
}