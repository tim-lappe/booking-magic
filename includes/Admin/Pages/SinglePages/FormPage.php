<?php


namespace TL_Booking\Admin\Pages\SinglePages;


use TL_Booking\Admin\Tables\FormListTable;

class FormPage extends PageBase {

	public function __construct( ) {
		parent::__construct( __("Form", TLBM_TEXT_DOMAIN), "booking-magic-form" );

		$this->parent_slug = "booking-magic";
	}

	public function ShowPageContent() {
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php _e("Forms", TLBM_TEXT_DOMAIN) ?></h1>
			<a class="page-title-action" href="<?php echo admin_url('post-new.php?post_type=' . TLBM_PT_FORMULAR); ?>"><?php _e("Add New Form", TLBM_TEXT_DOMAIN) ?></a>
			<hr class="wp-header-end">
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
		<?php
	}
}