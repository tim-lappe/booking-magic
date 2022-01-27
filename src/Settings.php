<?php


namespace TLBM;


use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;

class Settings
{
    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    /**
     * @param SettingsManagerInterface $settingsManager
     */
    public function __construct(SettingsManagerInterface $settingsManager)
    {
        $this->settingsManager = $settingsManager;
        add_action("admin_init", array($this, "registerSettings"));
    }

    public function registerSettings()
    {
        $this->settingsManager->loadSettings();
    }
}