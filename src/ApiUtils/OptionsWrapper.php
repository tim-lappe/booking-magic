<?php

namespace TLBM\ApiUtils;

use TLBM\ApiUtils\Contracts\OptionsInterface;

class OptionsWrapper implements OptionsInterface
{

    /**
     * @param string $option
     * @param mixed $default
     *
     * @return mixed
     */
    public function getOption(string $option, $default = false)
    {
        return get_option($option, $default);
    }

    /**
     * @param string $option
     * @param mixed $value
     * @param string|null $autoload
     *
     * @return void
     */
    public function updateOption(string $option, $value, $autoload = null): void
    {
        update_option($option, $value, $autoload);
    }

    /**
     * @param string $id
     * @param string $title
     * @param callable|null $callback
     * @param string $page
     *
     * @return void
     */
    public function addSettingsSection(string $id, string $title, ?callable $callback, string $page)
    {
        add_settings_section($id, $title, $callback, $page);
    }

    /**
     * @param string $option_group
     * @param string $option_name
     * @param array $args
     *
     * @return void
     */
    public function registerSetting(string $option_group, string $option_name, array $args = [])
    {
        register_setting($option_group, $option_name, $args);
    }

    /**
     * @param string $id
     * @param string $title
     * @param callable|null $callback
     * @param string $page
     * @param string $section
     * @param array $args
     *
     * @return void
     */
    public function addSettingsField(string $id, string $title, ?callable $callback, string $page, string $section = 'default', array $args = [])
    {
        add_settings_field($id, $title, $callback, $page, $section, $args);
    }
}