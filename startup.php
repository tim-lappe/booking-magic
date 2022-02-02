<?php

use DI\ContainerBuilder;
use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;
use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Ajax\Contracts\AjaxManagerInterface;
use TLBM\EnqueueAssets;
use TLBM\MainFactory;
use TLBM\PluginActivation;
use TLBM\RegisterShortcodes;
use TLBM\Request\Contracts\RequestManagerInterface;

if ( ! defined ( 'ABSPATH' )) {
    return;
}


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
    if(in_array(plugin_basename(TLBM_PLUGIN_FILE), apply_filters('active_plugins', get_option('active_plugins')))) {
        add_action("init", function () {
            $requestManager = MainFactory::get(RequestManagerInterface::class);
            $requestManager->init();

            $ajaxManager = MainFactory::get(AjaxManagerInterface::class);
            $ajaxManager->initMainAjaxFunction();
        });

        add_action("admin_init", function () {
            $settingsManager = MainFactory::get(SettingsManagerInterface::class);
            $settingsManager->loadSettings();
        });

        add_action("admin_menu", function () {
            $adminPageManager = MainFactory::get(AdminPageManagerInterface::class);
            $adminPageManager->loadMenuPages();
        });

        $tlbmContainer->get(RegisterShortcodes::class);
        $tlbmContainer->get(EnqueueAssets::class);
        $tlbmContainer->get(FormElementsCollectionInterface::class);

    }
} catch (Exception $e) {
    if(WP_DEBUG) {
        var_dump($e);
    }
}