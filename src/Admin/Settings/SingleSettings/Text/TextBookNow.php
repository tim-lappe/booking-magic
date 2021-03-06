<?php

namespace TLBM\Admin\Settings\SingleSettings\Text;

use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class TextBookNow extends SettingsBase
{

    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("text", "text_book_now", $localization->getText("Button 'Book Now'", TLBM_TEXT_DOMAIN), $localization->getText("Book Now", TLBM_TEXT_DOMAIN));
    }

    public function display()
    {
        ?>
        <label>
            <input type="text" value="<?php echo $this->escaping->escAttr($this->getValue()); ?>" class="regular-text" name="<?php echo $this->escaping->escAttr($this->optionName); ?>">
        </label>
        <?php
    }
}