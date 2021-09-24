<?php


namespace TL_Booking;


use TL_Booking\Admin\Pages\PageManager;

class AdminPages {

	public function __construct() {
		add_action("admin_menu", array($this, "RegisterAdminPages"));
	}

	public function RegisterAdminPages() {
		PageManager::RegisterPages();
	}
}