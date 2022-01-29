<?php

namespace TLBM\Validation;

use TLBM\Entity\Calendar;
use TLBM\Validation\Contracts\CalendarEntityValidatorInterface;

class CalendarEntityValidator implements CalendarEntityValidatorInterface
{

    /**
     * @var Calendar
     */
    private Calendar $calendar;

    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * @return array
     */
    public function isTitleValid(): array
    {
        $errors = array();
        if(empty($this->calendar->getTitle())) {
            $errors[] = __("The title is too short", TLBM_TEXT_DOMAIN);
        }

        return $errors;
    }

    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        return array_merge(
            $this->isTitleValid()
        );
    }
}