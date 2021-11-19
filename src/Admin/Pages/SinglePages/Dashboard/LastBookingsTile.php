<?php


namespace TLBM\Admin\Pages\SinglePages\Dashboard;


use TLBM\Admin\Tables\BookingListTable;class LastBookingsTile extends DashboardTile {

	public function __construct() {
		parent::__construct( __("Last Bookings", TLBM_TEXT_DOMAIN) );
	}

	public function PrintBody(): void {
		$bookings = new BookingListTable();
		$bookings->slim = true;
		$bookings->prepare_items();
		$bookings->display();
	}
}