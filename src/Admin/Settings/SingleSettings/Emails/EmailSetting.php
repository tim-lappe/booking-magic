<?php


namespace TLBM\Admin\Settings\SingleSettings\Emails;


use TLBM\Admin\Settings\SingleSettings\SettingsBase;

abstract class EmailSetting extends SettingsBase
{

    public function __construct($option_name, $title, $default_subject)
    {
        parent::__construct("emails", $option_name, $title, ["subject" => $default_subject,
            "message" => $this->getDefaultTemplate(),
            "enabled" => "on"
        ]);
    }

    abstract public function getDefaultTemplate(): string;

    public function getValue()
    {
        $value            = parent::getValue();
        $value['message'] = urldecode($value['message']);

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isValueValid($value): bool
    {
        if ( !isset($value['subject']) || !isset($value['message']) || !isset($value['enabled'])) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        $value = $this->getValue();

        return $value && $value['enabled'] == "yes";
    }

    public function display()
    {
        $opt = $this->getValue();
        ?>

        <label>
            <select name="<?php echo $this->escaping->escAttr($this->optionName); ?>[enabled]">
                <option <?php selected("yes", $opt['enabled']) ?> value="yes">
                    <?php $this->localization->echoText("Enabled", TLBM_TEXT_DOMAIN) ?>
                </option>
                <option <?php selected("no", $opt['enabled']) ?> value="no">
                    <?php $this->localization->echoText("Disabled", TLBM_TEXT_DOMAIN) ?>
                </option>
            </select>
        </label><br><br>
        <label>
            <?php $this->localization->echoText("Subject", TLBM_TEXT_DOMAIN) ?><br>
            <input type="text" class="regular-text" name="<?php echo $this->escaping->escAttr($this->optionName); ?>[subject]" value="<?php echo $this->escaping->escAttr($opt['subject']); ?>">
        </label><br><br>
        <label>
            <?php $this->localization->echoText("Message", TLBM_TEXT_DOMAIN) ?><br>
            <div class="tlbm-html-editor" data-name="<?php echo $this->escaping->escAttr($this->optionName); ?>[message]" data-value="<?php echo $this->escaping->escAttr(urlencode($opt['message'])); ?>"></div>
        </label>

        <?php
    }
}