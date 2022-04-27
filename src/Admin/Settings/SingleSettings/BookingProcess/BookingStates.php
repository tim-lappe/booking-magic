<?php


namespace TLBM\Admin\Settings\SingleSettings\BookingProcess;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

//TODO: Booking States Setting neu schreiben

class BookingStates extends SettingsBase
{

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

	/**
	 * @var EscapingInterface
	 */
    protected EscapingInterface $escaping;

	/**
	 * @param EscapingInterface $escaping
	 * @param LocalizationInterface $localization
	 */
    public function __construct(EscapingInterface $escaping, LocalizationInterface $localization)
    {
        $this->localization = $localization;
        $this->escaping = $escaping;
        parent::__construct("booking_process", "booking_states", $this->localization->getText("Booking States", TLBM_TEXT_DOMAIN), $this->getDefaultStates());
    }

    /**
     * @return array[]
     */
    public function getDefaultStates(): array
    {
        return array(array("name" => "new",
            "title" => $this->localization->getText("New", TLBM_TEXT_DOMAIN),
            "enabled" => true,
            "default" => true,
            "color" => "#94dcf2",
            "custom" => false
        ),
            array("name" => "processing",
                "title" => $this->localization->getText("Processing", TLBM_TEXT_DOMAIN),
                "enabled" => false,
                "default" => false,
                "color" => "#f5c842",
                "custom" => false
            ),
            array("name" => "confirmed",
                "title" => $this->localization->getText("Confirmed", TLBM_TEXT_DOMAIN),
                "enabled" => true,
                "default" => false,
                "color" => "#5ce072",
                "custom" => false
            ),
            array("name" => "appeared",
                "title" => $this->localization->getText("Appeared", TLBM_TEXT_DOMAIN),
                "enabled" => false,
                "default" => false,
                "color" => "#277534",
                "custom" => false
            ),
            array("name" => "not_appeared",
                "title" => $this->localization->getText("Not Appeared", TLBM_TEXT_DOMAIN),
                "enabled" => false,
                "default" => false,
                "color" => "#a10b0b",
                "custom" => false
            ),
            ["name" => "cancelled",
                "title" => $this->localization->getText("Cancelled", TLBM_TEXT_DOMAIN),
                "enabled" => true,
                "default" => false,
                "color" => "#fa3434",
                "custom" => false
            ]
        );
    }

    public function getStatesKeyValue(): array
    {
        $states       = $this->getValue();
        $stateskeyval = array();
        foreach ($states as $state) {
            $stateskeyval[$state['name']] = $state['title'];
        }

        return $stateskeyval;
    }

    public function getStateByName($name): array
    {
        $name   = empty($name) ? $this->settingsManager->getValue(DefaultBookingState::class) : $name;
        $name   = strtolower($name);

        $states = $this->getValue();
        foreach ($states as $state) {
            if ($state['name'] == $name) {
                return $state;
            }
        }

        return array();
    }

    public function display()
    {
        $states = $this->getValue();
        ?>
        <table class="tlbm-inner-settings-table">
            <thead>
            <tr>
                <th><?php $this->localization->echoText("Enabled", TLBM_TEXT_DOMAIN) ?></th>
                <th><?php $this->localization->echoText("Name", TLBM_TEXT_DOMAIN) ?></th>
                <th><?php $this->localization->echoText("Title", TLBM_TEXT_DOMAIN) ?></th>
                <th><?php $this->localization->echoText("Color", TLBM_TEXT_DOMAIN) ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($states as $key => $state) { ?>
                <tr>
                    <td>
                        <label>
                            <input type="checkbox" name="<?php echo $this->escaping->escAttr($this->optionName); ?>[<?php echo $this->escaping->escAttr($key); ?>][enabled]" <?php
                            checked(boolval($state['enabled'])) ?> value="true">
                        </label>
                    </td>
                    <td>
                        <label>
                            <input class="tlbm-status-name-input" readonly type="text" value="<?php echo $this->escaping->escAttr($state['name']) ?>" name="<?php echo $this->escaping->escAttr($this->optionName) ?>[<?php echo $this->escaping->escAttr($key) ?>][name]">
                        </label>
                    </td>
                    <td>
                        <label>
                            <input type="text" class="regular-text tlbm-settings-table-short-input" name="<?php
                            echo $this->escaping->escAttr($this->optionName) ?>[<?php
                            echo $this->escaping->escAttr($key) ?>][title]" value="<?php
                            echo $this->escaping->escAttr($state['title']); ?>">
                        </label>
                    </td>
                    <td>
                        <label>
                            <input type="color" class="tlbm-settings-table-short-input" name="<?php
                            echo $this->escaping->escAttr($this->optionName) ?>[<?php
                            echo $this->escaping->escAttr($key) ?>][color]" value="<?php
                            echo $this->escaping->escAttr($state['color']); ?>">
                        </label>
                    </td>
                    <?php
                    if (isset($state['custom']) && $state['custom']): ?>
                        <td>
                            <input type='hidden' name="<?php
                            echo $this->escaping->escAttr($this->optionName); ?>[<?php
                            echo $this->escaping->escAttr($key); ?>][custom]" value='true'>
                            <a class='button-status-delete button-link-delete' href='#'><span
                                        class="dashicons dashicons-trash"></span></a>
                        </td>
                    <?php
                    else: ?>
                        <td>
                            <input type='hidden' name="<?php
                            echo $this->escaping->escAttr($this->optionName); ?>[<?php
                            echo $this->escaping->escAttr($key); ?>][custom]" value=''>
                        </td>
                    <?php
                    endif; ?>
                </tr>
                <?php
            } ?>
            <tr class="tlbm-booking-states-edit" data-nametag="<?php echo $this->escaping->escAttr($this->optionName); ?>" data-count="<?php echo sizeof($states) ?>">
                <td>
                    <button class="button tlbm-add-booking-state"><?php $this->localization->echoText("Add Custom Status", TLBM_TEXT_DOMAIN) ?></button>
                </td>
            </tr>
            </tbody>
        </table>
        <?php
    }
}