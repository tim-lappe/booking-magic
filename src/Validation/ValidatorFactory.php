<?php

namespace TLBM\Validation;

use Throwable;
use TLBM\Entity\Calendar;
use TLBM\Entity\Rule;
use TLBM\Entity\RuleAction;
use TLBM\Entity\RulePeriod;
use TLBM\Entity\TimeSlot;
use TLBM\MainFactory;
use TLBM\Validation\Contracts\CalendarEntityValidatorInterface;
use TLBM\Validation\Contracts\RulesActionEntityValidatorInterface;
use TLBM\Validation\Contracts\RulesEntityValidatorInterface;
use TLBM\Validation\Contracts\RulesPeriodEntityValidatorInterface;
use TLBM\Validation\Contracts\TimeSlotEntityValidatorInterface;

abstract class ValidatorFactory
{

    /**
     * @param Rule $rule
     *
     * @return RulesEntityValidatorInterface|null
     */
    public static function createRuleValidator(Rule $rule): ?RulesEntityValidatorInterface
    {
        try {
            return MainFactory::create(RulesEntityValidatorInterface::class, ["rule" => $rule]);
        } catch (Throwable $exception) {
            return null;
        }
    }

    /**
     * @param Calendar $calendar
     *
     * @return CalendarEntityValidatorInterface|null
     */
    public static function createCalendarValidator(Calendar $calendar): ?CalendarEntityValidatorInterface
    {
        try {
            return MainFactory::create(CalendarEntityValidatorInterface::class, ["calendar" => $calendar]);
        } catch (Throwable $exception) {
            return null;
        }
    }

    /**
     * @param RuleAction $ruleAction
     *
     * @return RulesActionEntityValidatorInterface|null
     */
    public static function createRuleActionValidator(RuleAction $ruleAction): ?RulesActionEntityValidatorInterface
    {
        try {
            return MainFactory::create(RulesActionEntityValidatorInterface::class, ["ruleAction" => $ruleAction]);
        } catch (Throwable $exception) {
            return null;
        }
    }

    /**
     * @param RulePeriod $rulePeriod
     *
     * @return RulesPeriodEntityValidatorInterface|null
     */
    public static function createRulePeriodValidator(RulePeriod $rulePeriod): ?RulesPeriodEntityValidatorInterface {
        try {
            return MainFactory::create(RulesPeriodEntityValidatorInterface::class, ["rulePeriod" => $rulePeriod]);
        } catch (Throwable $exception) {
            return null;
        }
    }

    /**
     * @param TimeSlot $timeSlot
     *
     * @return TimeSlotEntityValidatorInterface|null
     */
    public static function createTimeSlotValidator(TimeSlot $timeSlot): ?TimeSlotEntityValidatorInterface {
        try {
            return MainFactory::create(TimeSlotEntityValidatorInterface::class, ["timeSlot" => $timeSlot]);
        } catch (Throwable $exception) {
            return null;
        }
    }
}