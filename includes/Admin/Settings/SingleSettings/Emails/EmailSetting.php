<?php


namespace TL_Booking\Admin\Settings\SingleSettings\Emails;


use TL_Booking\Admin\Settings\SingleSettings\SettingsBase;

abstract class EmailSetting extends SettingsBase {

	public function __construct( $option_name, $title, $default_subject) {
		parent::__construct( "emails", $option_name, $title,
			array(
				"subject" => $default_subject,
				"message" => $this->GetDefaultTemplate()
			)
		);
	}

	function PrintField() {
		$opt = get_option($this->option_name);
		if(!isset($opt['subject']) || !isset($opt['message'])) {
			$opt = $this->default_value;
		}
		?>

		<label>
			<?php echo __("Subject", TLBM_TEXT_DOMAIN) ?><br>
			<input type="text" class="regular-text" name="<?php echo $this->option_name ?>[subject]" value="<?php echo $opt['subject']; ?>">
		</label><br><br>
		<label>
			<?php echo __("Message", TLBM_TEXT_DOMAIN) ?><br>
			<textarea class="regular-text tlbm-admin-textarea" name="<?php echo $this->option_name ?>[message]"><?php echo $opt['message']; ?></textarea>
		</label>

		<?php
	}

	abstract function GetDefaultTemplate(): string;
}