<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


use TLBM\Admin\Tables\BookingListTable;
use TLBM\CMS\Contracts\LocalizationInterface;
use TLBM\MainFactory;

class LastBookingsTile extends DashboardTile
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct($localization->__("Last Bookings", TLBM_TEXT_DOMAIN));
    }

    public function displayBody(): void
    {
        $bookings       = MainFactory::create(BookingListTable::class);
        $bookings->slim = true;
        $bookings->prepare_items();
        $bookings->display();
    }
}