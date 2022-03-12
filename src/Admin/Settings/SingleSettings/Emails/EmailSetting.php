<?php


namespace TLBM\Admin\Settings\SingleSettings\Emails;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;

abstract class EmailSetting extends SettingsBase
{

    public function __construct($option_name, $title, $default_subject)
    {
        parent::__construct("emails", $option_name, $title, ["subject" => $default_subject,
            "message" => $this->getDefaultTemplate()
        ]);
    }

    abstract public function getDefaultTemplate(): string;

    public function getValue()
    {
        $value            = parent::getValue();
        $value['message'] = urldecode($value['message']);

        return $value;
    }

    public function display()
    {
        $opt = $this->getValue();
        if ( !isset($opt['subject']) || !isset($opt['message'])) {
            $opt = $this->defaultValue;
        }
        ?>

        <label>
            <?php
            echo $this->localization->__("Subject", TLBM_TEXT_DOMAIN) ?><br>
            <input type="text" class="regular-text" name="<?php
            echo $this->optionName ?>[subject]" value="<?php
            echo $opt['subject']; ?>">
        </label><br><br>
        <label>
            <?php
            echo $this->localization->__("Message", TLBM_TEXT_DOMAIN) ?><br>
            <div class="tlbm-html-editor" data-name="<?php
            echo $this->optionName ?>[message]" data-value="<?php
            echo urlencode($opt['message']); ?>"></div>
        </label>

        <?php
    }
}