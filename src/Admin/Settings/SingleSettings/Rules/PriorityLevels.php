<?php

namespace TLBM\Admin\Settings\SingleSettings\Rules;

use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class PriorityLevels extends SettingsBase
{
    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("rules", "priority_levels", $localization->__("Priority Levels", TLBM_TEXT_DOMAIN), array(
            $localization->__("Low", TLBM_TEXT_DOMAIN),
            $localization->__("Between Low and Medium", TLBM_TEXT_DOMAIN),
            $localization->__("Medium", TLBM_TEXT_DOMAIN),
            $localization->__("Between Medium and High", TLBM_TEXT_DOMAIN),
            $localization->__("High", TLBM_TEXT_DOMAIN),
            $localization->__("Very High", TLBM_TEXT_DOMAIN)
        ));
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        $value =  parent::getValue();

        if(!is_array($value)) {
            $rows = explode("\n", $value);
            foreach ($rows as &$row) {
                $row = trim($row);
            }
            $value = $rows;
        }

        return $value;
    }

    public function display()
    {
        $value = $this->getValue();
        $value = implode("\n", $value);

        ?>
        <label>
            <textarea class="regular-text tlbm-admin-textarea" name="<?php echo $this->optionName ?>"><?php echo $value ?></textarea>
        </label>
        <?php
    }
}