<?php

namespace TLBM\Admin\Settings\Contracts;

use TLBM\Admin\Settings\SingleSettings\SettingsBase;

interface SettingsManagerInterface
{

    /**
     * @param object $setting
     *
     * @return bool
     */
    public function registerSetting(object $setting): bool;

    /**
     * @param string $name
     * @param string $title
     *
     * @return bool
     */
    public function registerSettingsGroup(string $name, string $title): bool;

    /**
     * @template T of SettingsBase
     *
     * @param class-string<T> $name
     *
     * @return ?T
     */
    public function getSetting(string $name): ?SettingsBase;

    /**
     * @return array
     */
    public function getAllSettings(): array;

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getSettingsGroup(string $name): ?string;

    /**
     * @return array
     */
    public function getAllSettingsGroups(): array;

    /**
     * @return void
     */
    public function loadSettings(): void;

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getValue(string $name);

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function setValue(string $name, $value);
}