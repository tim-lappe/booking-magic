<?php


namespace TLBM\Admin\Settings\SingleSettings\BookingProcess;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

//TODO: Booking States Setting neu schreiben

class BookingStates extends SettingsBase
{

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct(LocalizationInterface $localization)
    {
        $this->localization = $localization;
        parent::__construct("booking_process", "booking_states", $this->localization->__("Booking States", TLBM_TEXT_DOMAIN), $this->getDefaultStates());
    }

    /**
     * @return array[]
     */
    public function getDefaultStates(): array
    {
        return array(
            array(
                "name"    => "new",
                "title"   => $this->localization->__("New", TLBM_TEXT_DOMAIN),
                "enabled" => true,
                "default" => true,
                "color"   => "#94dcf2",
                "custom"  => false
            ),
            array(
                "name"    => "processing",
                "title"   => $this->localization->__("Processing", TLBM_TEXT_DOMAIN),
                "enabled" => false,
                "default" => false,
                "color"   => "#f5c842",
                "custom"  => false
            ),
            array(
                "name"    => "confirmed",
                "title"   => $this->localization->__("Confirmed", TLBM_TEXT_DOMAIN),
                "enabled" => true,
                "default" => false,
                "color"   => "#5ce072",
                "custom"  => false
            ),
            array(
                "name"    => "appeared",
                "title"   => $this->localization->__("Appeared", TLBM_TEXT_DOMAIN),
                "enabled" => false,
                "default" => false,
                "color"   => "#277534",
                "custom"  => false
            ),
            array(
                "name"    => "not_appeared",
                "title"   => $this->localization->__("Not Appeared", TLBM_TEXT_DOMAIN),
                "enabled" => false,
                "default" => false,
                "color"   => "#a10b0b",
                "custom"  => false
            ),
            array(
                "name"    => "canceled",
                "title"   => $this->localization->__("Canceled", TLBM_TEXT_DOMAIN),
                "enabled" => true,
                "default" => false,
                "color"   => "#fa3434",
                "custom"  => false
            )
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
                <th><?php
                    echo $this->localization->__("Enabled", TLBM_TEXT_DOMAIN) ?></th>
                <th><?php
                    echo $this->localization->__("Name", TLBM_TEXT_DOMAIN) ?></th>
                <th><?php
                    echo $this->localization->__("Title", TLBM_TEXT_DOMAIN) ?></th>
                <th><?php
                    echo $this->localization->__("Color", TLBM_TEXT_DOMAIN) ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($states as $key => $state) { ?>
                <tr>
                    <td>
                        <label>
                            <input type="checkbox" name="<?php
                            echo $this->optionName ?>[<?php
                            echo $key ?>][enabled]" <?php
                            checked(boolval($state['enabled'])) ?> value="true">
                        </label>
                    </td>
                    <td>
                        <label>
                            <input class="tlbm-status-name-input" readonly type="text" value="<?php
                            echo $state['name'] ?>" name="<?php
                            echo $this->optionName ?>[<?php
                            echo $key ?>][name]">
                        </label>
                    </td>
                    <td>
                        <label>
                            <input type="text" class="regular-text tlbm-settings-table-short-input" name="<?php
                            echo $this->optionName ?>[<?php
                            echo $key ?>][title]" value="<?php
                            echo $state['title']; ?>">
                        </label>
                    </td>
                    <td>
                        <label>
                            <input type="color" class="tlbm-settings-table-short-input" name="<?php
                            echo $this->optionName ?>[<?php
                            echo $key ?>][color]" value="<?php
                            echo $state['color']; ?>">
                        </label>
                    </td>
                    <?php
                    if (isset($state['custom']) && $state['custom']): ?>
                        <td>
                            <input type='hidden' name="<?php
                            echo $this->optionName ?>[<?php
                            echo $key ?>][custom]" value='true'>
                            <a class='button-status-delete button-link-delete' href='#'><span
                                        class="dashicons dashicons-trash"></span></a>
                        </td>
                    <?php
                    else: ?>
                        <td>
                            <input type='hidden' name="<?php
                            echo $this->optionName ?>[<?php
                            echo $key ?>][custom]" value=''>
                        </td>
                    <?php
                    endif; ?>
                </tr>
                <?php
            } ?>
            <tr class="tlbm-booking-states-edit" data-nametag="<?php
            echo $this->optionName ?>" data-count="<?php
            echo sizeof($states) ?>">
                <td>
                    <button class="button tlbm-add-booking-state"><?php
                        echo $this->localization->__("Add Custom Status", TLBM_TEXT_DOMAIN) ?></button>
                </td>
            </tr>
            </tbody>
        </table>
        <?php
    }
}