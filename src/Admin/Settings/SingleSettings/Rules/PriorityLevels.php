<?php

namespace TLBM\Admin\Settings\SingleSettings\Rules;

use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class PriorityLevels extends SettingsBase
{
    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("rules", "priority_levels", $localization->getText("Priority Levels", TLBM_TEXT_DOMAIN), [$localization->getText("Low", TLBM_TEXT_DOMAIN),
            $localization->getText("Between Low and Medium", TLBM_TEXT_DOMAIN),
            $localization->getText("Medium", TLBM_TEXT_DOMAIN),
            $localization->getText("Between Medium and High", TLBM_TEXT_DOMAIN),
            $localization->getText("High", TLBM_TEXT_DOMAIN),
            $localization->getText("Very High", TLBM_TEXT_DOMAIN)
        ]);
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
            <textarea class="regular-text tlbm-admin-textarea" name="<?php echo $this->escaping->escAttr($this->optionName); ?>"><?php echo $this->escaping->escHtml($value); ?></textarea>
        </label>
        <?php
    }
}