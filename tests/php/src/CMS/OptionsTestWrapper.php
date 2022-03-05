<?php

namespace TLBMTEST\CMS;

use TLBM\ApiUtils\Contracts\OptionsInterface;

class OptionsTestWrapper implements OptionsInterface
{

    /**
     * @inheritDoc
     */
    public function getOption(string $option, $default = false)
    {
        return $default;
    }

    /**
     * @inheritDoc
     */
    public function updateOption(string $option, $value, $autoload = null): void
    {

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

    }
}