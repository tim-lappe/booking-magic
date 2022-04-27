<?php


namespace TLBM\Admin\Settings\SingleSettings\BookingProcess;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class DefaultBookingState extends SettingsBase
{
	/**
	 * @var EscapingInterface
	 */
    protected EscapingInterface $escaping;

	/**
	 * @param LocalizationInterface $localization
	 * @param EscapingInterface $escaping
	 */
    public function __construct(LocalizationInterface $localization, EscapingInterface $escaping)
    {
        $this->escaping = $escaping;
        parent::__construct("booking_process", "booking_default_state", $localization->getText("Default Booking State", TLBM_TEXT_DOMAIN), "new");
    }

    public function display()
    {
        ?>
        <label>
            <select name="<?php echo $this->escaping->escAttr($this->optionName) ?>">
                <?php
                $states = $this->settingsManager->getValue(BookingStates::class);
                foreach ($states as $state): ?>
                    <option value="<?php echo $this->escaping->escAttr($state['name']) ?>" <?php selected($state['name'] == $this->getValue()) ?>><?php
                        echo $this->escaping->escHtml($state['title']) ?></option>
                <?php
                endforeach; ?>
            </select>
        </label>
        <?php
    }
}