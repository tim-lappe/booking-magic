<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Pages\SinglePages\Dashboard\Dashboard;

class BookingMagicRoot extends PageBase {

	public function __construct( ) {
		parent::__construct( __("Booking Magic", TLBM_TEXT_DOMAIN), "booking-magic" );

		$this->menu_secondary_title = "Dashboard";
	}

	public function ShowPageContent() {
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php _e("Dashboard", TLBM_TEXT_DOMAIN) ?></h1>
			<hr class="wp-header-end">
            <?php
            $dashboard = new Dashboard();
            $dashboard->Print();
            ?>
		</div>
		<?php
	}
}