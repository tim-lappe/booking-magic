<?php


namespace TLBM\Admin\WpForm;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TLBM\Output\Html\FormContents;
use TLBM\Utilities\DateTimeTools;

class TimeSpanField extends FormFieldBase {

	public $setting = array();

	private $all_settings = array(
	       "years", "days", "hours", "minutes"
    );

	public function __construct( $name, $title, $setting = array("days", "hours"), $value = "" ) {
		parent::__construct( $name, $title, $value );

		$this->setting = $setting;
	}

	function OutputHtml() {
	    $timeparts = DateTimeTools::FromMinutesToTimeparts($this->value);
		?>
		<tr>
			<th scope="row"><label for="<?php echo $this->name ?>"><?php echo $this->title ?></label></th>
			<td>
				<?php foreach ($this->all_settings as $setting): ?>
                    <?php if(in_array($setting, $this->setting)): ?>
                        <div class="tlbm-timespan-part">
                            <?php if($setting == "hours"): ?>
                                <select name="<?php echo $this->name ?>_hours">
                                    <?php echo FormContents::GetTimeHoursSelectOptions($timeparts['hours']); ?>
                                </select>
                                <?php echo __("Hours", TLBM_PT_CALENDAR); ?>
                            <?php elseif($setting == "days"): ?>
                                <select name="<?php echo $this->name ?>_days">
                                    <?php for($i = 0; $i < 356; $i++): ?>
                                        <option <?php echo $timeparts['days'] == $i ? "selected='selected'" : "" ?> value="<?php echo $i ?>"><?php echo $i ?></option>
                                    <?php endfor; ?>
                                </select>
                                <?php echo __("Days", TLBM_PT_CALENDAR); ?>
                            <?php elseif($setting == "minutes"): ?>
                                <select name="<?php echo $this->name ?>_minutes">
                                    <?php for($i = 0; $i < 59; $i++): ?>
	                                    <?php echo FormContents::GetTimeHoursSelectOptions($timeparts['minutes']); ?>
                                    <?php endfor; ?>
                                </select>
                                <?php echo __("Minutes", TLBM_PT_CALENDAR); ?>
                            <?php elseif($setting == "years"): ?>
                                <select name="<?php echo $this->name ?>_years">
                                    <?php for($i = 0; $i < 10; $i++): ?>
                                        <option <?php echo $timeparts['years'] == $i ? "selected='selected'" : "" ?> value="<?php echo $i ?>"><?php echo $i ?></option>
                                    <?php endfor; ?>
                                </select>
                                <?php echo __("Years", TLBM_PT_CALENDAR); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
				<?php endforeach; ?>
			</td>
		</tr>
		<?php
	}
}