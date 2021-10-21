<?php


namespace TLBM\Admin\Settings\SingleSettings;


abstract class SettingsBase {

	public $option_group;
	public $option_name;
	public $default_value;
	public $description;
	public $title;

	public function __construct($option_group, $option_name, $title, $default_value = "", $description = "") {
		$this->option_name = $option_name;
		$this->option_group = $option_group;
		$this->title = $title;
		$this->default_value = $default_value;
		$this->description = $description;
	}

	public function PrintField() {
		?>
		<label>
			<input type="text" class="regular-text" name="<?php echo $this->option_name ?>" value="<?php echo get_option($this->option_name); ?>">
		</label>
		<?php
	}
}