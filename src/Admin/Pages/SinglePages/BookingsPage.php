<?php


namespace TLBM\Admin\Pages\SinglePages;

use Exception;
use TLBM\Admin\Tables\BookingListTable;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\MainFactory;


class BookingsPage extends PageBase
{

	/**
	 * @var EscapingInterface
	 */
    protected EscapingInterface $escaping;

	/**
	 * @param EscapingInterface $escaping
	 */
    public function __construct(EscapingInterface $escaping)
    {
        parent::__construct("Bookings", "booking-magic-bookings");
        $this->parentSlug = "booking-magic";
        $this->escaping = $escaping;
    }

    public function displayPageBody()
    {
        ?>
        <div class="tlbm-admin-page">
            <div class="tlbm-admin-page-tile">
                <form method="get">
                    <input type="hidden" name="page" value="<?php echo $this->escaping->escAttr($_REQUEST['page']) ?>"/>
                    <?php
                    try {
                        $bookingsListTable = MainFactory::create(BookingListTable::class);
                        $bookingsListTable->views();
                        $bookingsListTable->prepare_items();
                        $bookingsListTable->display();

                    } catch (Exception $exception) {
                        if(WP_DEBUG) {
                            var_dump($exception->getMessage());
                        }
                    }
                    ?>
                </form>
            </div>
        </div>
        <?php
    }
}