<?php


namespace TL_Booking\Email;


use TL_Booking\Admin\Settings\SettingsManager;

class MailSender {

	/**
	 * @param $to
	 * @param $email_option_name
	 * @param array $vars
	 *
	 * @return bool|mixed|void
	 */
	public static function SendTemplate($to, $email_option_name, $vars = array()) {
		$opt = get_option($email_option_name, SettingsManager::GetSetting($email_option_name)->default_value);
		if($opt) {
			$subject = $opt['subject'];
			$content = $opt['message'];
			$message = '<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>'.$subject.'</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/></head><body>';

			$inline_css = file_get_contents(TLBM_DIR . "/assets/css/mail/main.css");
			$message .= "<style>".$inline_css."</style>";
			$message .= $content;

			$message .= "</body></html>";
			$vars = $vars + self::GetDefaultTemplateVars();
			foreach ($vars as $key => $value) {
				$message = str_replace("{{".$key."}}", $value, $message);
			}

			return wp_mail($to, $subject, $message);
		}

		return false;
	}

	/**
	 * @return array
	 */
	private static function GetDefaultTemplateVars(): array {
		return array(
			"site_name" => get_bloginfo("name")
		);
	}
}