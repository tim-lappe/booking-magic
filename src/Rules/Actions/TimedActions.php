<?php

namespace TLBM\Rules\Actions;

use TLBM\Entity\RuleAction;
use TLBM\Utilities\ExtendedDateTime;
use Traversable;

class TimedActions
{
    /**
     * @var RuleAction[]
     */
    private array $ruleActions;

    /**
     * @var ExtendedDateTime
     */
    private ExtendedDateTime $dateTime;

    /**
     * @param RuleAction[] $ruleActions
     * @param ExtendedDateTime $dateTime
     */
    public function __construct(ExtendedDateTime $dateTime, array $ruleActions)
    {
        $this->ruleActions = $ruleActions;
        $this->dateTime    = $dateTime;
    }

    /**
     * @return RuleAction[]
     */
    public function getRuleActions(): array
    {
        return $this->ruleActions;
    }

    /**
     * @return ExtendedDateTime
     */
    public function getDateTime(): ExtendedDateTime
    {
        return $this->dateTime;
    }

    /**
     * @param ExtendedDateTime $dateTime
     */
    public function setDateTime(ExtendedDateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }
}