<?php

namespace TLBM\Validation;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\Calendar;
use TLBM\MainFactory;
use TLBM\Validation\Contracts\CalendarEntityValidatorInterface;

class CalendarEntityValidator implements CalendarEntityValidatorInterface
{

    /**
     * @var Calendar
     */
    private Calendar $calendar;

    /**
     * @param Calendar $calendar
     */
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
        $localization = MainFactory::get(LocalizationInterface::class);

        if(empty($this->calendar->getTitle())) {
            $errors[] = $localization->__("The title is too short", TLBM_TEXT_DOMAIN);
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