<?php


namespace TL_Booking\Admin\Settings\SingleSettings\BookingProcess;


use TL_Booking\Admin\Settings\SingleSettings\SettingsBase;

class BookingStates extends SettingsBase {

	public function __construct() {
		parent::__construct( "booking_process", "booking_states", __("Booking States", TLBM_TEXT_DOMAIN), self::GetDefaultStates());
	}


	function PrintField() {
        $states = get_option("booking_states", self::GetDefaultStates());
     //s   $states = self::GetDefaultStates();
        ?>
        <table class="tlbm-inner-settings-table">
            <thead>
                <tr>
                    <th><?php echo __("Enabled") ?></th>
                    <th><?php echo __("Name") ?></th>
                    <th><?php echo __("Title") ?></th>
                    <th><?php echo __("Color") ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($states as $key => $state) { ?>
                    <tr>
                        <td>
                            <label>
                                <input type="checkbox" name="<?php echo $this->option_name ?>[<?php echo $key ?>][enabled]" <?php checked(boolval($state['enabled'])) ?> value="true">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input class="tlbm-status-name-input" readonly type="text" value="<?php echo $state['name'] ?>" name="<?php echo $this->option_name ?>[<?php echo $key ?>][name]">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input type="text" class="regular-text tlbm-settings-table-short-input" name="<?php echo $this->option_name ?>[<?php echo $key ?>][title]" value="<?php echo $state['title']; ?>">
                            </label>
                        </td>
                        <td>
                            <label>
                                <input type="color" class="tlbm-settings-table-short-input" name="<?php echo $this->option_name ?>[<?php echo $key ?>][color]" value="<?php echo $state['color']; ?>">
                            </label>
                        </td>
                        <?php if(isset($state['custom']) && boolval($state['custom'])): ?>
                            <td>
                                <input type='hidden' name="<?php echo $this->option_name ?>[<?php echo $key ?>][custom]" value='true'>
                                <a class='button-status-delete button-link-delete' href='#'><span class="dashicons dashicons-trash"></span></a>
                            </td>
                        <?php else: ?>
                        <td>
                            <input type='hidden' name="<?php echo $this->option_name ?>[<?php echo $key ?>][custom]" value=''>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php } ?>
                <tr class="tlbm-booking-states-edit" nametag="<?php echo $this->option_name ?>" count="<?php echo sizeof($states) ?>">
                    <td><button class="button tlbm-add-booking-state"><?php echo __("Add Custom Status", TLBM_TEXT_DOMAIN) ?></button></td>
                </tr>
            </tbody>
        </table>
        <?php
	}

	/**
	 * @return array[]
	 */
	public static function GetDefaultStates(): array {
        return array(
            array(
                "name" => "new",
                "title" => __("New", TLBM_TEXT_DOMAIN),
                "enabled" => true,
                "default" => true,
                "color" => "#94dcf2",
                "custom" => false
            ),
	        array(
		        "name" => "processing",
		        "title" => __("Processing", TLBM_TEXT_DOMAIN),
		        "enabled" => false,
		        "default" => false,
		        "color" => "#f5c842",
		        "custom" => false
	        ),
            array(
	            "name" => "confirmed",
	            "title" => __("Confirmed", TLBM_TEXT_DOMAIN),
	            "enabled" => true,
                "default" => false,
                "color" => "#5ce072",
	            "custom" => false
            ),
	        array(
		        "name" => "appeared",
		        "title" => __("Appeared", TLBM_TEXT_DOMAIN),
		        "enabled" => false,
		        "default" => false,
		        "color" => "#277534",
		        "custom" => false
	        ),
	        array(
		        "name" => "not_appeared",
		        "title" => __("Not Appeared", TLBM_TEXT_DOMAIN),
		        "enabled" => false,
		        "default" => false,
		        "color" => "#a10b0b",
		        "custom" => false
	        ),
            array(
	            "name" => "canceled",
	            "title" => __("Canceled", TLBM_TEXT_DOMAIN),
	            "enabled" => true,
	            "default" => false,
                "color" => "#fa3434",
	            "custom" => false
            )
        );
	}
}