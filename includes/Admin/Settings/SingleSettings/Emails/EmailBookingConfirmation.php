<?php


namespace TL_Booking\Admin\Settings\SingleSettings\Emails;


use TL_Booking\Admin\Settings\SingleSettings\SettingsBase;

class EmailBookingConfirmation extends SettingsBase {

	public function __construct() {
		parent::__construct( "emails", "email_booking_confirmation", __("Booking Confirmation", TLBM_TEXT_DOMAIN), "" );
	}

	function PrintField() {
		?>
		<label>
			<textarea class="regular-text tlbm-admin-textarea" name="<?php echo $this->option_name ?>"><?php echo get_option($this->option_name); ?></textarea>
		</label>
		<?php
	}
}