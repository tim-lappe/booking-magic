<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Tables\FormListTable;

class FormPage extends PageBase {

	public function __construct( ) {
		parent::__construct( __("Form", TLBM_TEXT_DOMAIN), "booking-magic-form" );

		$this->parent_slug = "booking-magic";
	}

    public function DisplayDefaultHeadBar() {
        ?>
        <a href="<?php echo admin_url('post-new.php?post_type=' . TLBM_PT_BOOKING); ?>" class="button button-primary tlbm-admin-button-bar"><?php _e("Add New Form", TLBM_TEXT_DOMAIN) ?></a>
        <?php
    }


    public function DisplayPageBody() {
		?>
		<div class="tlbm-admin-page">
            <div class="tlbm-admin-page-tile">
                <form method="get">
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                    <?php
                    $post_list_table = new FormListTable();
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