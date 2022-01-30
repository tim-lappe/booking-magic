<?php

namespace TLBM\Admin\Settings\SingleSettings\Rules;

use TLBM\Admin\Settings\SingleSettings\SettingsBase;

class PriorityLevels extends SettingsBase
{
    public function __construct()
    {
        parent::__construct("rules", "priority_levels", __("Priority Levels", TLBM_TEXT_DOMAIN), array(
            __("Low", TLBM_TEXT_DOMAIN),
            __("Between Low and Medium", TLBM_TEXT_DOMAIN),
            __("Medium", TLBM_TEXT_DOMAIN),
            __("Between Medium and High", TLBM_TEXT_DOMAIN),
            __("High", TLBM_TEXT_DOMAIN),
            __("Very High")
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