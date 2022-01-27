<?php


namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\Pages\SinglePages\Dashboard\Contracts\DashboardInterface;

class BookingMagicRoot extends PageBase
{

    /**
     * @var DashboardInterface
     */
    private DashboardInterface $dashboard;

    /**
     * @param DashboardInterface $dashboard
     */
    public function __construct(DashboardInterface $dashboard)
    {
        parent::__construct(__("Booking Magic", TLBM_TEXT_DOMAIN), "booking-magic");
        $this->menu_secondary_title = "Dashboard";
        $this->dashboard            = $dashboard;
    }

    /**
     * @return string
     */
    public function getHeadTitle(): string
    {
        return __("Dashboard", TLBM_TEXT_DOMAIN);
    }

    /**
     * @return void
     */
    public function displayPageBody()
    {
        ?>
        <div class="wrap">
            <?php
            $this->dashboard->display();
            ?>
        </div>
        <?php
    }
}