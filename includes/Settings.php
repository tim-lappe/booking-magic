<?php


namespace TL_Booking;


use TL_Booking\Admin\Settings\SettingsManager;

class Settings {

	public function __construct() {
		SettingsManager::DefineSettings();

		add_action("admin_init", array($this, "RegisterSettings"));
	}

	public function RegisterSettings() {
		SettingsManager::RegisterSettings();
	}
}