<?php

namespace TLBM\ApiUtils\Contracts;

interface OptionsInterface
{
    /**
     * @param string $option
     * @param mixed $default
     *
     * @return mixed
     */
    public function getOption( string $option, $default = false );

    /**
     * @param string $option
     * @param mixed $value
     * @param string|bool $autoload
     *
     * @return void
     */
    public function updateOption( string $option, $value, $autoload = null ): void;

    /**
     * @param string $id
     * @param string $title
     * @param ?callable $callback
     * @param string $page
     *
     * @return mixed
     */
    public function addSettingsSection( string $id, string $title, ?callable $callback, string $page );

    /**
     * @param string $option_group
     * @param string $option_name
     * @param array $args
     *
     * @return mixed
     */
    public function registerSetting( string $option_group, string $option_name, array $args = array() );

    /**
     * @param string $id
     * @param string $title
     * @param ?callable $callback
     * @param string $page
     * @param string $section
     * @param array $args
     *
     * @return mixed
     */
    public function addSettingsField( string $id, string $title, ?callable $callback, string $page, string $section = 'default', array $args = array() );
}