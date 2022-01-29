<?php

use DI\ContainerBuilder;
use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;
use TLBM\Ajax\Contracts\AjaxManagerInterface;
use TLBM\EnqueueAssets;
use TLBM\PluginActivation;
use TLBM\RegisterShortcodes;
use TLBM\Request;
use TLBM\Settings;
use TLBM\WpRegisterAdminPages;

if ( ! defined ( 'ABSPATH' )) {
    return;
}

(function () {
    if (WP_DEBUG) {
        error_reporting ( E_ALL );
        ini_set ( 'error_reporting', E_ALL );
        ini_set ( 'display_errors', true );
        ini_set ( 'display_startup_errors', true );
        ini_set ( "error_log", "/tmp/phplog.txt" );
    }

    try {
        $tlbmContainerBuilder = new ContainerBuilder();
        $tlbmContainerBuilder->addDefinitions ( __DIR__ . "/dependency.php" );

        if ( ! WP_DEBUG) {
            $tlbmContainerBuilder->enableCompilation ( sys_get_temp_dir () );
        }

        $tlbmContainer = $tlbmContainerBuilder->build ();
        $GLOBALS['TLBM_DICONTAINER'] = $tlbmContainer;


        $tlbmContainer->get ( PluginActivation::class );

        /**
         * Check if plugin is already acitvated
         */
        if(in_array(plugin_basename(TLBM_PLUGIN_FILE), apply_filters('active_plugins', get_option('active_plugins')))){

            $tlbmContainer->get(Settings::class);
            $tlbmContainer->get(RegisterShortcodes::class);
            $tlbmContainer->get(EnqueueAssets::class);
            $tlbmContainer->get(AjaxManagerInterface::class);

            $tlbmContainer->get(Request::class);
            $tlbmContainer->get(WpRegisterAdminPages::class);

            /**
             * Register all FormElements for the Formeditor
             */
            $tlbmContainer->get(FormElementsCollectionInterface::class);
        }
    } catch (Exception $e) {
        var_dump ( $e );
    }
})();