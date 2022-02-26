<?php

namespace TLBM\CMS;

class PluginActivationWrapper implements Contracts\PluginActivationInterface
{
    /**
     * @param string $pluginFile
     * @param callable $callback
     *
     * @return void
     */
    public function registerActivationHook(string $pluginFile, callable $callback)
    {
        register_activation_hook($pluginFile, $callback);

    }

    /**
     * @param string $pluginFile
     * @param callable $callback
     *
     * @return void
     */
    public function registerDeactivationHook(string $pluginFile, callable $callback)
    {
        register_deactivation_hook($pluginFile, $callback);
    }
}