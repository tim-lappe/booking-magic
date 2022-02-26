<?php


namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Admin\Pages\SinglePages\Dashboard\Contracts\DashboardInterface;
use TLBM\CMS\Contracts\LocalizationInterface;

class BookingMagicRoot extends PageBase
{

    /**
     * @var DashboardInterface
     */
    private DashboardInterface $dashboard;

    /**
     * @var LocalizationInterface
     */
    private LocalizationInterface $localization;

    /**
     * @param DashboardInterface $dashboard
     * @param LocalizationInterface $localization
     */
    public function __construct(DashboardInterface $dashboard, LocalizationInterface $localization)
    {
        $this->localization = $localization;

        parent::__construct($this->localization->__("Booking Magic", TLBM_TEXT_DOMAIN), "booking-magic");
        $this->menuSecondaryTitle = "Dashboard";
        $this->dashboard          = $dashboard;
    }

    /**
     * @return string
     */
    public function getHeadTitle(): string
    {
        return $this->localization->__("Dashboard", TLBM_TEXT_DOMAIN);
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