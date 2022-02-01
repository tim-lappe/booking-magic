<?php

namespace TLBM\Ajax;

use DateTime;
use DI\DependencyException;
use DI\NotFoundException;
use TLBM\Ajax\Contracts\AjaxFunctionInterface;
use TLBM\MainFactory;
use TLBM\Rules\Contracts\RuleActionsManagerInterface;
use TLBM\Rules\Contracts\RulesQueryInterface;
use TLBM\Rules\Actions\ActionsMerging;
use TLBM\Utilities\ExtendedDateTime;

class GetMergedActions implements AjaxFunctionInterface
{

    /**
     * @var RuleActionsManagerInterface
     */
    private RuleActionsManagerInterface $ruleActionsManager;

    public function __construct(RuleActionsManagerInterface $ruleActionsManager)
    {
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
     * @param mixed $assocData
     *
     * @return array
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute($assocData): array
    {
        /**
         * @var RulesQueryInterface $query
         */
        $query = MainFactory::create(RulesQueryInterface::class);
        if (isset($assocData['actionType'])) {
            if (is_array($assocData['actionType'])) {
                $query->setActionTypes($assocData['actionType']);
            } else {
                $query->setActionTypes(array($assocData['actionType']));
            }
        }

        $fromDateTime = new ExtendedDateTime();
        $fromDateTime->setFromObject($assocData['fromDateTime']);

        $toDateTime = new ExtendedDateTime();
        $toDateTime->setFromObject($assocData['toDateTime']);

        $focusedDateTime = new ExtendedDateTime();
        if(isset($assocData['options']['focusedDateTime'])) {
            $focusedDateTime->setFromObject($assocData['options']['focusedDateTime']);
        } else {
            $focusedDateTime->setInvalid(true);
        }

        if ( !$fromDateTime->isInvalid() && !$toDateTime->isInvalid()) {
            $query->setDateTimeRange($fromDateTime, $toDateTime);

        } elseif (!$focusedDateTime->isInvalid()) {
            $query->setDateTime($focusedDateTime);

        } else {
            return array(
                "error" => true
            );
        }

        $actionsReader  = new ActionsMerging($this->ruleActionsManager, $query);
        $mergedCollection = $actionsReader->getRuleActionsMerged();

        return array(
            "actionsResult" => $mergedCollection
        );
    }
}