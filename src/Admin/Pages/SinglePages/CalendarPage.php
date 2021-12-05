<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Tables\CalendarGroupTable;
use TLBM\Admin\Tables\CalendarListTable;

class CalendarPage extends PageBase {

	public function __construct() {
		parent::__construct(__("Calendars", TLBM_TEXT_DOMAIN), "booking-magic-calendar" );

		$this->parent_slug = "booking-magic";
	}

    function GetHeadTitle(): string {
        return __("Calendars");
    }

    function DisplayDefaultHeadBar() {
        ?>
        <a href="<?php echo admin_url('admin.php?page=booking-calendar-edit'); ?>" class="button button-secondary tlbm-admin-button-bar"><?php _e("Add New Group", TLBM_TEXT_DOMAIN) ?></a>
        <a href="<?php echo admin_url('admin.php?page=booking-calendar-edit'); ?>" class="button button-primary tlbm-admin-button-bar"><?php _e("Add New Calendar", TLBM_TEXT_DOMAIN) ?></a>
        <?php
    }

    public function DisplayPageBody() {
		?>
        <div class="tlbm-admin-page">
            <div class="tlbm-admin-page-tile">
                <form method="get">
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                    <?php
                        $post_list_table = new CalendarListTable();
                        $post_list_table->views();
                        $post_list_table->prepare_items();
                        $post_list_table->display();
                    ?>
                </form>
            </div>
            <div class="tlbm-admin-page-tile">
                <h2>Groups</h2>
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
        </div>
		<?php
	}
}