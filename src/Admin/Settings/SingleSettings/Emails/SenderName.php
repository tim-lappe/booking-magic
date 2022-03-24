<?php

namespace TLBM\Admin\Settings\SingleSettings\Emails;

use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class SenderName extends SettingsBase
{
    public function __construct(LocalizationInterface $localization)
    {
        parent::__construct("emails", "mail_sender_name", $localization->getText("Sender name", TLBM_TEXT_DOMAIN), get_bloginfo("name"));
    }

    public function display()
    {
        ?>
        <label>
            <input type="text" class="regular-text" name="<?php
            echo $this->optionName ?>" value="<?php
            echo $this->getValue(); ?>">
        </label>
        <?php
    }
}