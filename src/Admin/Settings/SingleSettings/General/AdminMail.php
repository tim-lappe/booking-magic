<?php


namespace TLBM\Admin\Settings\SingleSettings\General;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\ApiUtils\Contracts\OptionsInterface;

class AdminMail extends SettingsBase
{

    public function __construct(LocalizationInterface $localization, OptionsInterface $options)
    {
        parent::__construct("general", "admin_mail", $localization->getText("Admin Mail", TLBM_TEXT_DOMAIN), $options->getOption("admin_email"));
    }

    public function display()
    {
        ?>
        <label>
            <input type="email" class="regular-text" name="<?php
            echo $this->optionName ?>" value="<?php
            echo $this->getValue(); ?>">
        </label>
        <?php
    }
}