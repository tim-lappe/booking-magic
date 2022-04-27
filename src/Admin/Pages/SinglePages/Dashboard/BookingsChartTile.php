<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Booking\Statistics;
use TLBM\Utilities\ExtendedDateTime;

class BookingsChartTile extends DashboardTile
{
    /**
     * @var Statistics
     */
    private Statistics $statistics;

	/**
	 * @var EscapingInterface
	 */
    private EscapingInterface $escaping;

	/**
	 * @param LocalizationInterface $localization
	 * @param Statistics $statistics
	 * @param EscapingInterface $escaping
	 */
    public function __construct(LocalizationInterface $localization, Statistics $statistics, EscapingInterface $escaping)
    {
        $this->escaping = $escaping;
        $this->statistics = $statistics;
        parent::__construct($localization->getText("Bookings", TLBM_TEXT_DOMAIN));
    }

    public function displayBody(): void
    {
        $dt = new ExtendedDateTime();
        $dt->setDay($dt->getDay() - 365);

        $bookingsCount = $this->statistics->getBookingsCountMonthly($dt, new ExtendedDateTime());
        ?>
        <div class="tlbm-admin-line-chart" data-json="<?php echo $this->escaping->escAttr(urlencode(json_encode($bookingsCount))); ?>">

        </div>
        <?php
    }
}