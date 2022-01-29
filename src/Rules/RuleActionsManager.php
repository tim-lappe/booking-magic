<?php


namespace TLBM\Rules;


use DateTime;
use phpDocumentor\Reflection\Types\This;
use TLBM\Entity\Calendar;
use TLBM\Entity\RuleAction;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;
use TLBM\Rules\Contracts\RulesManagerInterface;
use TLBM\Rules\RuleActions\RuleActionMergingBase;

class RuleActionsManager implements RuleActionsManagerInterface
{

    /**
     * @var array
     */
    public array $ruleActions = array();

    /**
     * @var RulesManagerInterface
     */
    private RulesManagerInterface $rulesManager;


    /**
     * @param RulesManagerInterface $rulesManager
     */
    public function __construct(RulesManagerInterface $rulesManager)
    {
        $this->rulesManager = $rulesManager;
    }

    /**
     * @param string $term
     * @param string $class
     *
     * @return bool
     */
    public function registerActionMerger(string $term, string $class): bool
    {
        if(!isset($this->ruleActions[$term])) {
            $this->ruleActions[$term] = $class;
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getAllActionsMerger(): array {
        return $this->ruleActions;
    }

    /**
     *
     * @param RuleAction $action
     *
     * @return ?RuleActionMergingBase
     */
    public function getActionMerger(RuleAction $action): ?RuleActionMergingBase
    {
        if (isset($this->ruleActions[$action->getActionType()])) {
            return new $this->ruleActions[$action->getActionType()]($action);
        }

        return null;
    }

    /**
     * @param Calendar $calendar
     * @param DateTime $dateTime
     *
     * @return RuleAction[]
     */
    public function getActionsForDateTime(Calendar $calendar, DateTime $dateTime): array
    {
        $rules          = $this->rulesManager->getAllRulesForCalendarForDateTime($calendar->getId(), $dateTime);
        $actions        = array();
        $workingactions = array();

        foreach ($rules as $rule) {
            $actions = array_merge($actions, $rule->action->actions_list);
        }

        foreach ($actions as $action) {
            if (RuleActionHandler::GetActionHandler($action)->WorksAtTime($dateTime)) {
                $workingactions[] = $action;
            }
        }

        return $workingactions;
    }
}