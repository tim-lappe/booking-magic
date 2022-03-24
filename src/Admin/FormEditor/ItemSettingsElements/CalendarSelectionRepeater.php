<?php

namespace TLBM\Admin\FormEditor\ItemSettingsElements;

use TLBM\Calendar\CalendarHelper;
use TLBM\MainFactory;

class CalendarSelectionRepeater extends ElementSetting
{
    /**
     * @var array
     */
    public array $calendarKeyValues = [];

    /**
     * @param string $name
     * @param string $title
     * @param string $default_value
     * @param bool $readonly
     * @param bool $must_unique
     * @param array $forbidden_values
     * @param string $category_title
     */
    public function __construct($name, $title, string $default_value = "", bool $readonly = false, bool $must_unique = false, array $forbidden_values = [], string $category_title = "General")
    {
        parent::__construct($name, $title, $default_value, $readonly, $must_unique, $forbidden_values, $category_title);
        $this->type = "calendar_selection_repeater";

        $calendarHelper          = MainFactory::create(CalendarHelper::class);
        $this->calendarKeyValues = $calendarHelper->getGroupAndCalendarKeyValues();
    }
}