<?php


namespace TLBM\Rules;


use DateTime;
use TLBM\Entity\Calendar;
use TLBM\Entity\RuleAction;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;
use TLBM\Rules\Contracts\RulesManagerInterface;
use TLBM\Rules\RuleActions\DateTimeSlotActionMerge;
use TLBM\Rules\RuleActions\DateTimeTimeSlotActionMerge;
use TLBM\Rules\RuleActions\RuleActionMergingBase;

class RuleActionsManager implements RuleActionsManagerInterface
{

    public array $ruleActions = array(
        "date_slot" => DateTimeSlotActionMerge::class,
        "time_slot" => DateTimeTimeSlotActionMerge::class
    );
    private RulesManagerInterface $rulesManager;

    public function __construct(RulesManagerInterface $rulesManager)
    {
        $this->rulesManager = $rulesManager;
    }

    public function registerActionMerger(string $term, $class)
    {
        $this->ruleActions[$term] = $class;
    }


    /**
     *
     * @param RuleAction $action
     *
     * @return ?RuleActionMergingBase
     */
    public function getActionMerger(RuleAction $action): ?RuleActionMergingBase
    {
        if (isset($this->ruleActions[$action->GetActionType()])) {
            return new $this->ruleActions[$action->GetActionType()]($action);
        }

        return null;
    }


    /**
     * @param Calendar $calendar
     * @param DateTime $date_time
     *
     * @return RuleAction[]
     */
    public function getActionsForDateTime(Calendar $calendar, DateTime $date_time): array
    {
        $rules          = $this->rulesManager->getAllRulesForCalendarForDateTime($calendar->GetId(), $date_time);
        $actions        = array();
        $workingactions = array();

        foreach ($rules as $rule) {
            $actions = array_merge($actions, $rule->action->actions_list);
        }

        foreach ($actions as $action) {
            if (RuleActionHandler::GetActionHandler($action)->WorksAtTime($date_time)) {
                $workingactions[] = $action;
            }
        }

        return $workingactions;
    }
}