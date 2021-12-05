<?php


namespace TLBM\Admin\Pages\SinglePages;


use TLBM\Admin\Pages\SinglePages\Dashboard\Dashboard;

class BookingMagicRoot extends PageBase {

	public function __construct( ) {
		parent::__construct( __("Booking Magic", TLBM_TEXT_DOMAIN), "booking-magic" );

		$this->menu_secondary_title = "Dashboard";
	}

    public function GetHeadTitle(): string {
        return __("Dashboard", TLBM_TEXT_DOMAIN);
    }

    public function DisplayPageBody() {
		?>
		<div class="wrap">
            <?php
            $dashboard = new Dashboard();
            $dashboard->Print();
            ?>
		</div>
		<?php
	}
}