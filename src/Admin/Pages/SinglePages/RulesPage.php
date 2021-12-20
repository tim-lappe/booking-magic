<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Tables\AllRulesListTable;
use TLBM\Admin\Tables\RulesListTable;

class RulesPage extends PageBase {

	public function __construct( ) {
		parent::__construct( __("Rules", TLBM_TEXT_DOMAIN), "booking-magic-rules" );

		$this->parent_slug = "booking-magic";
	}

    public function DisplayDefaultHeadBar() {
        ?>
        <a href="<?php echo admin_url('admin.php?page=booking-calendar-rule-edit'); ?>" class="button button-primary tlbm-admin-button-bar"><?php _e("Add New Rule", TLBM_TEXT_DOMAIN) ?></a>
        <?php
    }


    public function DisplayPageBody() {
		?>
        <div class="tlbm-admin-page">
            <div class="tlbm-admin-page-tile">
                <form method="get">
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                    <?php
                    $post_list_table = new AllRulesListTable();
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