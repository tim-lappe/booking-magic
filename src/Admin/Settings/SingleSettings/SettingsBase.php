<?php


namespace TLBM\Admin\Settings\SingleSettings;


use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;

abstract class SettingsBase
{
    /**
     * @var string
     */
    public string $optionGroup;

    /**
     * @var string
     */
    public string $optionName;

    /**
     * @var mixed
     */
    public $defaultValue;

    /**
     * @var string
     */
    public string $description;

    /**
     * @var string
     */
    public string $title;

    /**
     * @var SettingsManagerInterface
     */
    protected SettingsManagerInterface $settingsManager;

    /**
     * @param string $optionGroup
     * @param string $optionName
     * @param string $title
     * @param mixed $defaultValue
     * @param string $description
     */
    public function __construct(
        string $optionGroup,
        string $optionName,
        string $title,
        $defaultValue = "",
        string $description = ""
    ) {
        $this->optionName  = $optionName;
        $this->optionGroup = $optionGroup;
        $this->title       = $title;
        $this->defaultValue = $defaultValue;
        $this->description   = $description;
    }

    public function getSettingsManager(): SettingsManagerInterface
    {
        return $this->settingsManager;
    }

    public function setSettingsManager(SettingsManagerInterface $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return get_option($this->optionName, $this->defaultValue);
    }

    public function display()
    {
        ?>
        <label>
            <input type="text" class="regular-text" name="<?php
            echo $this->optionName ?>" value="<?php
            echo get_option($this->optionName); ?>">
        </label>
        <?php
    }
}