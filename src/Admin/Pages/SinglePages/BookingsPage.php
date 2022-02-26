<?php


namespace TLBM\Admin\Pages\SinglePages;

use Exception;
use TLBM\Admin\Tables\BookingListTable;
use TLBM\MainFactory;


class BookingsPage extends PageBase
{

    public function __construct()
    {
        parent::__construct("Bookings", "booking-magic-bookings");
        $this->parentSlug = "booking-magic";
    }

    public function displayDefaultHeadBar()
    {
        ?>
        <a href="<?php
        echo admin_url('post-new.php?post_type=' . TLBM_PT_BOOKING); ?>"
           class="button button-primary tlbm-admin-button-bar"><?php
            _e("Add New Booking", TLBM_TEXT_DOMAIN) ?></a>
        <?php
    }

    public function displayPageBody()
    {
        ?>
        <div class="tlbm-admin-page">
            <div class="tlbm-admin-page-tile">
                <form method="get">
                    <input type="hidden" name="page" value="<?php
                    echo $_REQUEST['page'] ?>"/>
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