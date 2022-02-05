<?php

namespace TLBM\Admin\Settings\SingleSettings\Text;

use TLBM\Admin\Settings\SingleSettings\SettingsBase;

class TextBookNow extends SettingsBase
{

    public function __construct()
    {
        parent::__construct("text", "text_book_now", __("Button 'Book Now'", TLBM_TEXT_DOMAIN), __("Book Now", TLBM_TEXT_DOMAIN));
    }

    public function display()
    {
        ?>
        <label>
            <input type="text" value="<?php echo $this->getValue() ?>" class="regular-text" name="<?php echo $this->optionName ?>">
        </label>
        <?php
    }
}