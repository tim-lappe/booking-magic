<?php


namespace TLBM\Admin\Settings;


use InvalidArgumentException;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\BookingStates;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\SinglePageBooking;
use TLBM\Admin\Settings\SingleSettings\General\AdminMail;
use TLBM\Admin\Settings\SingleSettings\Emails\EmailBookingConfirmation;
use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\Admin\Settings\SingleSettings\Text\TextBookingReceived;
use TLBM\Admin\Settings\SingleSettings\Text\WeekdayLabels;

class SettingsManager {

	/**
	 * @var SettingsBase[]
	 */
	public static array $settings = array();

	public static array $groups = array();

	public static function DefineSettings() {
		self::$groups = array(
			"general" => __("General", TLBM_TEXT_DOMAIN),
			"booking_process" => __("Booking Process", TLBM_TEXT_DOMAIN),
			"emails" => __("E-Mails", TLBM_TEXT_DOMAIN),
			"text" => __("Text", TLBM_TEXT_DOMAIN),
			"advanced" => __("Advanced", TLBM_TEXT_DOMAIN)
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
			new BookingStates(),
			/**
			 * E-Mail
			 */
			new EmailBookingConfirmation(),
			/**
			 * Text
			 */
			new WeekdayLabels(),
			new TextBookingReceived(),

		);
	}

	/**
	 * @param $name
	 *
	 * @return SettingsBase
	 */
	public static function GetSetting($name): SettingsBase {
		foreach (self::$settings as $setting) {
			if($setting->option_name == $name) {
				return $setting;
			}
		}

		throw new InvalidArgumentException();
	}

	public static function RegisterSettings() {
		foreach (self::$groups as $key => $group) {
			add_settings_section("tlbm_" . $key . "_section", $group, null, "tlbm_settings_" . $key);
		}

		foreach (self::$settings as $setting) {
			register_setting( "tlbm_" . $setting->option_group, $setting->option_name, array("default" => $setting->default_value) );
			add_settings_field( "tlbm_" . $setting->option_group . "_" . $setting->option_name . "_field", $setting->title, array($setting, "PrintField"), 'tlbm_settings_' . $setting->option_group, "tlbm_" . $setting->option_group . "_section");
		}
	}
}