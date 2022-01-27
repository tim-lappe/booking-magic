<?php


namespace TLBM\Admin\Settings\SingleSettings;


use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;

abstract class SettingsBase
{
    /**
     * @var string
     */
    public string $option_group;

    /**
     * @var string
     */
    public string $option_name;

    /**
     * @var mixed
     */
    public $default_value;

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
     * @param string $option_group
     * @param string $option_name
     * @param string $title
     * @param mixed $default_value
     * @param string $description
     */
    public function __construct(
        string $option_group,
        string $option_name,
        string $title,
        $default_value = "",
        string $description = ""
    ) {
        $this->option_name   = $option_name;
        $this->option_group  = $option_group;
        $this->title         = $title;
        $this->default_value = $default_value;
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

    public function display()
    {
        ?>
        <label>
            <input type="text" class="regular-text" name="<?php
            echo $this->option_name ?>" value="<?php
            echo get_option($this->option_name); ?>">
        </label>
        <?php
    }
}