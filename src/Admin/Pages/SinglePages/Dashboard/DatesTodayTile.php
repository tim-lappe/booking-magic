<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


class DatesTodayTile extends DashboardTile
{

    public function __construct()
    {
        parent::__construct(__("Dates Today", TLBM_TEXT_DOMAIN));
    }

    public function PrintBody(): void
    {
    }
}