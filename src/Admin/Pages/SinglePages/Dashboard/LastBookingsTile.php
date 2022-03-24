<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


use TLBM\Admin\Tables\BookingListTable;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\MainFactory;

class LastBookingsTile extends DashboardTile
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct($localization->getText("Last Bookings", TLBM_TEXT_DOMAIN));
        $this->setGrowLevel(2);
    }

    public function displayBody(): void
    {
        $bookings = MainFactory::create(BookingListTable::class);
        $bookings->setSlim(true);
        $bookings->setItemsPerPage( 3);
        $bookings->prepare_items();
        $bookings->display();
    }
}