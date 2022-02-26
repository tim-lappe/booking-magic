<?php


namespace TLBM\Admin\Settings;

use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\SettingsBase;
use TLBM\CMS\Contracts\OptionsInterface;

class SettingsManager implements SettingsManagerInterface
{
    /**
     * @var SettingsBase[]
     */
    public array $settings = array();

    /**
     * @var array
     */
    public array $groups = array();

    /**
     * @var OptionsInterface
     */
    private OptionsInterface $options;

    /**
     * @param OptionsInterface $options
     */
    public function __construct(OptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * @param object $setting
     *
     * @return bool
     */
    public function registerSetting(object $setting): bool
    {
        if ( !isset($this->settings[get_class($setting)]) && $setting instanceof SettingsBase) {
            $this->settings[get_class($setting)] = $setting;
            $setting->setSettingsManager($this);

            return true;
        }

        return false;
    }

    /**
     * @param string $name
     * @param string $title
     *
     * @return bool
     */
    public function registerSettingsGroup(string $name, string $title): bool
    {
        if ( !isset($this->groups[$name])) {
            $this->groups[$name] = $title;

            return true;
        }

        return false;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getValue(string $name)
    {
        $setting = $this->getSetting($name);
        if ($setting) {
            return $setting->getValue();
        }

        return null;
    }


    /**
     * @template T of SettingsBase
     *
     * @param class-string<T> $name
     *
     * @return ?T
     */
    public function getSetting(string $name): ?SettingsBase
    {
        if (isset($this->settings[$name])) {
            return $this->settings[$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function setValue(string $name, $value)
    {
        $setting = $this->getSetting($name);
        if ($setting) {
            $this->options->updateOption($setting->optionName, $setting->defaultValue);
        }
    }

    /**
     * @param string $name
     *
     * @return ?string
     */
    public function getSettingsGroup(string $name): ?string
    {
        return $this->groups[$name] ?? null;
    }

    /**
     * @return void
     */
    public function loadSettings(): void
    {
        foreach ($this->groups as $key => $group) {
            $this->options->addSettingsSection("tlbm_" . $key . "_section", $group, null, "tlbm_settings_" . $key);
        }

        foreach ($this->settings as $setting) {
            $this->options->registerSetting("tlbm_" . $setting->optionGroup, $setting->optionName, array("default" => $setting->defaultValue));
            $this->options->addSettingsField(
                "tlbm_" . $setting->optionGroup . "_" . $setting->optionName . "_field", $setting->title, array($setting, "display"), 'tlbm_settings_' . $setting->optionGroup, "tlbm_" . $setting->optionGroup . "_section"
            );
        }
    }

    /**
     * @return array
     */
    public function getAllSettings(): array
    {
        return $this->settings;
    }

    /**
     * @return array
     */
    public function getAllSettingsGroups(): array
    {
        return $this->groups;
    }
}