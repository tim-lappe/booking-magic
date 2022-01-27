<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Tables\BookingListTable;
use TLBM\Booking\BookingManager;
use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Utilities\Contracts\DateTimeToolsInterface;

class BookingsPage extends PageBase
{
    /**
     * @var CalendarManagerInterface
     */
    private CalendarManagerInterface $calendarManager;

    /**
     * @var DateTimeToolsInterface
     */
    private DateTimeToolsInterface $dateTimeTools;

    public function __construct(CalendarManagerInterface $calendarManager, DateTimeToolsInterface $dateTimeTools)
    {
        parent::__construct("Bookings", "booking-magic-bookings");
        $this->calendarManager = $calendarManager;
        $this->dateTimeTools   = $dateTimeTools;
        $this->parent_slug     = "booking-magic";
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
        global $wp_query;
        ?>
        <div class="tlbm-admin-page">
            <div class="tlbm-admin-page-tile">
                <form method="get">
                    <input type="hidden" name="page" value="<?php
                    echo $_REQUEST['page'] ?>"/>
                    <?php
                    $bookings        = BookingManager::GetAllBookings();
                    $post_list_table = new BookingListTable($this->calendarManager, $this->dateTimeTools);
                    $post_list_table->views();
                    $post_list_table->prepare_items();
                    $post_list_table->display();
                    ?>
                </form>
            </div>
        </div>
        <?php
    }
}