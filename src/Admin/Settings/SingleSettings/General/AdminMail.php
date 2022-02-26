<?php


namespace TLBM\Admin\Settings\SingleSettings\General;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\CMS\Contracts\LocalizationInterface;
use TLBM\CMS\Contracts\OptionsInterface;

class AdminMail extends SettingsBase
{

    public function __construct(LocalizationInterface $localization, OptionsInterface $options)
    {
        parent::__construct("general", "admin_mail", $localization->__("Admin Mail", TLBM_TEXT_DOMAIN), $options->getOption("admin_email"));
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