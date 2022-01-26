<?php


namespace TLBM\Admin\Settings\SingleSettings\General;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;

class AdminMail extends SettingsBase
{

    public function __construct()
    {
        parent::__construct("general", "admin_mail", __("Admin Mail", TLBM_TEXT_DOMAIN), get_option("admin_email"));
    }

    public function PrintField()
    {
        ?>
        <label>
            <input type="email" class="regular-text" name="<?php
            echo $this->option_name ?>" value="<?php
            echo get_option($this->option_name); ?>">
        </label>
        <?php
    }
}