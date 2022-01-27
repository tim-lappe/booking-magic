<?php

namespace TLBM\Ajax;

use DateTime;
use DI\DependencyException;
use DI\FactoryInterface;
use DI\NotFoundException;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;
use TLBM\Ajax\Contracts\AjaxFunctionInterface;
use TLBM\Booking\BookableSlot;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;
use TLBM\Rules\Contracts\RulesManagerInterface;
use TLBM\Rules\Contracts\RulesQueryInterface;
use TLBM\Rules\RuleActions\ActionsMerging;
use TLBM\Rules\RulesQuery;

class GetBookingOptions implements AjaxFunctionInterface
{

    /**
     * @var FactoryInterface
     */
    private FactoryInterface $factory;

    /**
     * @var RuleActionsManagerInterface
     */
    private RuleActionsManagerInterface $ruleActionsManager;

    public function __construct(FactoryInterface $factory, RuleActionsManagerInterface $ruleActionsManager)
    {
        $this->factory            = $factory;
        $this->ruleActionsManager = $ruleActionsManager;
    }

    /**
     * @return string
     */
    public function getFunctionName(): string
    {
        return "getBookingOptions";
    }

    /**
     * @param mixed $data
     *
     * @return array
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute($data): array
    {
        $query = $this->factory->make(RulesQueryInterface::class);

        if (isset($data->action_type)) {
            if (is_array($data->action_type)) {
                $query->setActionTypes($data->action_type);
            } else {
                $query->setActionTypes(array($data->action_type));
            }
        }

        if (isset($data->from_tstamp) && isset($data->to_tstamp)) {
            $from = new DateTime();
            $from->setTimestamp($data->from_tstamp);
            $to = new DateTime();
            $to->setTimestamp($data->to_tstamp);
            $query->setDateTimeRange($from, $to);
        } elseif (isset($data->options) && isset($data->options->focused_tstamp)) {
            $dt = new DateTime();
            $dt->setTimestamp($data->options->focused_tstamp);
            $query->setDateTime($dt);
        } else {
            return array(
                "error" => true
            );
        }

        $result         = array();
        $actions_reader = new ActionsMerging($this->ruleActionsManager, $query);
        $result         = $actions_reader->getRuleActionsMerged();

        return array(
            "merged_actions" => $result,
            "bookable_slots" => array(
                new BookableSlot(1642501787),
                new BookableSlot(1642503787)
            )
        );
    }
}