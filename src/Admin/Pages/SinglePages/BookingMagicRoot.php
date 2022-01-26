<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\Dashboard\Dashboard;

class BookingMagicRoot extends PageBase
{

    /**
     * @param AdminPageManagerInterface $adminPageManager
     */
    public function __construct(AdminPageManagerInterface $adminPageManager)
    {
        parent::__construct($adminPageManager, __("Booking Magic", TLBM_TEXT_DOMAIN), "booking-magic");
        $this->menu_secondary_title = "Dashboard";
    }

    public function getHeadTitle(): string
    {
        return __("Dashboard", TLBM_TEXT_DOMAIN);
    }

    public function displayPageBody()
    {
        ?>
        <div class="wrap">
            <?php
            $dashboard = new Dashboard();
            $dashboard->Print();
            ?>
        </div>
        <?php
    }
}