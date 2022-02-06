<?php

namespace TLBM\Rules;

use TLBM\Entity\Rule;
use TLBM\Rules\Actions\TimedActions;
use TLBM\Utilities\ExtendedDateTime;

class TimedRules
{
    /**
     * @var Rule[]
     */
    private array $rules;

    /**
     * @var ExtendedDateTime
     */
    private ExtendedDateTime $dateTime;

    public function __construct(ExtendedDateTime $dateTime, array $rules)
    {
        $this->rules    = $rules;
        $this->dateTime = $dateTime;
    }

    /**
     * @return TimedActions
     */
    public function getTimedActions(): TimedActions
    {
        $actions = [];
        foreach ($this->getRules() as $rule) {
            $actions = array_merge($actions, $rule->getActions()->toArray());
        }
        return new TimedActions($this->dateTime, $actions);
    }

    /**
     * @return Rule[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @param Rule[] $rules
     */
    public function setRules(array $rules): void
    {
        $this->rules = $rules;
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