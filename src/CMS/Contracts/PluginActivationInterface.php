<?php

namespace TLBM\CMS\Contracts;

interface PluginActivationInterface
{
    public function registerActivationHook(string $pluginFile, callable $callback);
    public function registerDeactivationHook(string $pluginFile, callable $callback);
}