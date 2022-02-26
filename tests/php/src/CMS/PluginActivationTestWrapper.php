<?php

namespace TLBMTEST\CMS;

use TLBM\CMS\Contracts\PluginActivationInterface;

class PluginActivationTestWrapper implements PluginActivationInterface
{

    /**
     * @param string $pluginFile
     * @param callable $callback
     *
     * @return void
     */
    public function registerActivationHook(string $pluginFile, callable $callback)
    {

    }

    /**
     * @param string $pluginFile
     * @param callable $callback
     *
     * @return void
     */
    public function registerDeactivationHook(string $pluginFile, callable $callback)
    {

    }
}