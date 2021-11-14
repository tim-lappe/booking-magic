<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Tables\BookingListTable;
use TLBM\Admin\Tables\CalendarGroupTable;
use TLBM\Admin\Tables\CalendarListTable;
use TLBM\Booking\BookingManager;

class CalendarPage extends PageBase {

	public function __construct() {
		parent::__construct(__("Calendar", TLBM_TEXT_DOMAIN), "booking-magic-calendar" );

		$this->parent_slug = "booking-magic";
	}

	public function ShowPageContent() {
		?>
		<div class="wrap">
			<h2 class="wp-heading-inline-secondary"><?php _e("Calendars", TLBM_TEXT_DOMAIN) ?></h2>
			    <a class="page-title-action" href="<?php echo admin_url('post-new.php?post_type=' . TLBM_PT_CALENDAR); ?>"><?php _e("Add New Calendar", TLBM_TEXT_DOMAIN) ?></a>
            <hr class="wp-header-end">
            <form method="get">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
				<?php
                    $post_list_table = new CalendarListTable();
                    $post_list_table->views();
                    $post_list_table->prepare_items();
                    $post_list_table->display();
				?>
            </form>
            <h2 class="wp-heading-inline-secondary"><?php _e("Groups", TLBM_TEXT_DOMAIN) ?></h2>
                <a class="page-title-action" href="<?php echo admin_url('post-new.php?post_type=' . TLBM_PT_CALENDAR_GROUPS); ?>"><?php _e("Add New Group", TLBM_TEXT_DOMAIN) ?></a>
            <hr class="wp-header-end">
            <form method="get">
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
				<?php
                    $group_list_table = new CalendarGroupTable();
                    $group_list_table->views();
                    $group_list_table->prepare_items();
                    $group_list_table->display();
				?>
            </form>
		</div>
		<?php
	}
}