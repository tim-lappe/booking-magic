<?php


namespace TL_Booking\Admin\Settings;


use TL_Booking\Admin\Settings\SingleSettings\BookingProcess\SinglePageBooking;
use TL_Booking\Admin\Settings\SingleSettings\General\AdminMail;
use TL_Booking\Admin\Settings\SingleSettings\Emails\EmailBookingConfirmation;
use TL_Booking\Admin\Settings\SingleSettings\SettingsBase;

class SettingsManager {

	/**
	 * @var SettingsBase[]
	 */
	public static $settings = array();

	public static $groups = array();


	public static function LoadSettings() {
		self::$groups = array(
			"general" => __("General", TLBM_TEXT_DOMAIN),
			"booking_process" => __("Booking Process", TLBM_TEXT_DOMAIN),
			"emails" => __("E-Mails", TLBM_TEXT_DOMAIN)
		);

		self::$settings = array(
			/**
			 * General
			 */
			new AdminMail(),

			/**
			 * Booking Process,
			 */
			new SinglePageBooking(),

			/**
			 * E-Mail
			 */
			new EmailBookingConfirmation()


		);
	}

	public static function RegisterSettings() {
		if(sizeof(self::$settings) == 0) {
			self::LoadSettings();
		}

		foreach (self::$groups as $key => $group) {
			add_settings_section("tlbm_" . $key . "_section", $group, null, "tlbm_settings_" . $key);
		}

		foreach (self::$settings as $setting) {
			register_setting( "tlbm_" . $setting->option_group, $setting->option_name, array("default" => $setting->default_value) );
			add_settings_field( "tlbm_" . $setting->option_group . "_" . $setting->option_name . "_field", $setting->title, array($setting, "PrintField"), 'tlbm_settings_' . $setting->option_group, "tlbm_" . $setting->option_group . "_section");
		}
	}
}