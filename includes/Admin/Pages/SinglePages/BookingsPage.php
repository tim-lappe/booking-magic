<?php


namespace TL_Booking\Admin\Pages\SinglePages;


use TL_Booking\Admin\Tables\BookingListTable;
use TL_Booking\Booking\BookingManager;
use WP_Screen;

class BookingsPage extends PageBase {

	public function __construct() {
		parent::__construct( "Bookings", "booking-magic-bookings" );

		$this->parent_slug = "booking-magic";
	}

	public function ShowPageContent() {
	    global $wp_query;
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php _e("Bookings", TLBM_TEXT_DOMAIN) ?></h1>
			<a class="page-title-action" href="<?php echo admin_url('post-new.php?post_type=' . TLBM_PT_BOOKING); ?>"><?php _e("Add New Booking", TLBM_TEXT_DOMAIN) ?></a>
			<hr class="wp-header-end">
            <form method="get">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />

                <?php

                $bookings = BookingManager::GetAllBookings();

                if(sizeof($bookings) > 0) {
                    $post_list_table = new BookingListTable();
                    $post_list_table->views();
                    $post_list_table->prepare_items();
                    $post_list_table->display();
                } else {
                    ?>
                    There are no Bookings
                    <?php
                }
                ?>
            </form>
		</div>
		<?php
	}
}