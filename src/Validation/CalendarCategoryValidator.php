<?php

namespace TLBM\Validation;

use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\CalendarCategory;
use TLBM\MainFactory;
use TLBM\Validation\Contracts\CalendarCategoryEntityValidatorInterface;

class CalendarCategoryValidator implements CalendarCategoryEntityValidatorInterface
{
    /**
     * @var CalendarCategory
     */
    private CalendarCategory $calendarCategory;

    /**
     * @param CalendarCategory $calendarCategory
     */
    public function __construct(CalendarCategory $calendarCategory)
    {
        $this->calendarCategory = $calendarCategory;
    }

    /**
     * @inheritDoc
     */
    public function getValidationErrors(): array
    {
        return array_merge(
            $this->isTitleValid()
        );
    }

    public function isTitleValid(): array
    {
        $errors = array();
        $localization = MainFactory::get(LocalizationInterface::class);
        if(empty($this->calendarCategory->getTitle())) {
            $errors[] = $localization->getText("The title is too short", TLBM_TEXT_DOMAIN);
        }

        return $errors;
    }
}