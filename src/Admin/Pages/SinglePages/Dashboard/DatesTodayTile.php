<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


use TLBM\CMS\Contracts\LocalizationInterface;

class DatesTodayTile extends DashboardTile
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct($localization->__("Dates Today", TLBM_TEXT_DOMAIN));
    }

    public function displayBody(): void
    {
    }
}