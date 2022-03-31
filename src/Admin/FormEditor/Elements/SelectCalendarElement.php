<?php

namespace TLBM\Admin\FormEditor\Elements;

use TLBM\Admin\FormEditor\FormInputGenerator;
use TLBM\Admin\FormEditor\ItemSettingsElements\CalendarSelectionRepeater;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class SelectCalendarElement extends FormInputElem
{

    /**
     * @param LocalizationInterface $localization
     */
    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("field_select_calendar", $localization->getText("Calendar Selection", TLBM_TEXT_DOMAIN));

        $this->description  = $this->localization->getText("change the calendar in an appointment selection", TLBM_TEXT_DOMAIN);
        $this->menuCategory = "Calendar";

        $selectionSetting                    = new CalendarSelectionRepeater("calendars", $this->localization->getText("Calendars", TLBM_TEXT_DOMAIN));
        $selectionSetting->categoryTitle     = $localization->getText("Calendar Selection", TLBM_TEXT_DOMAIN);
        $selectionSetting->dataSourceProvier = "calendar_or_group";

        $this->addSettings($selectionSetting);
    }

    /**
     * @param LinkedFormData $linkedFormData
     * @param callable|null $displayChildren
     *
     * @return string
     */
    public function getFrontendContent(LinkedFormData $linkedFormData, callable $displayChildren = null): string
    {
        $generator = new FormInputGenerator($linkedFormData);

        return $generator->getSelect([]);
    }
}