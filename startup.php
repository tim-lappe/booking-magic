<?php

use DI\ContainerBuilder;
use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;
use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Ajax\Contracts\AjaxManagerInterface;
use TLBM\ApiUtils\Contracts\HooksInterface;
use TLBM\EnqueueAssets;
use TLBM\MainFactory;
use TLBM\PluginActivation;
use TLBM\RegisterShortcodes;
use TLBM\Request\Contracts\RequestManagerInterface;
use TLBM\Session\SessionManager;

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

    $tlbmContainer = $tlbmContainerBuilder->build ();
    $GLOBALS['TLBM_DICONTAINER'] = $tlbmContainer;
    $hooks = $tlbmContainer->get(HooksInterface::class);

    $tlbmContainer->get ( PluginActivation::class );

    /**
     * Check if plugin is already acitvated
     */
    if(in_array(plugin_basename(TLBM_PLUGIN_FILE), apply_filters('active_plugins', get_option('active_plugins')))) {
        $hooks->addAction("init", function () {
            if(get_locale() == "de_DE") {
                define("TLBM_NEWS_FEED_URL", "https://booking-magic.de/news-feed/");
            } else {
                define("TLBM_NEWS_FEED_URL", "https://booking-magic-plugin.com/news-feed/");
            }

            $requestManager = MainFactory::get(RequestManagerInterface::class);
            $requestManager->init();

            $ajaxManager = MainFactory::get(AjaxManagerInterface::class);
            $ajaxManager->initMainAjaxFunction();
        });

        $hooks->addAction("admin_init", function () {
            $settingsManager = MainFactory::get(SettingsManagerInterface::class);
            $settingsManager->loadSettings();
        });

        $hooks->addAction("admin_menu", function () {
            $adminPageManager = MainFactory::get(AdminPageManagerInterface::class);
            $adminPageManager->loadMenuPages();
        });

        $tlbmContainer->get(SessionManager::class);
        $tlbmContainer->get(RegisterShortcodes::class);
        $tlbmContainer->get(EnqueueAssets::class);
        $tlbmContainer->get(FormElementsCollectionInterface::class);

    }
} catch (Throwable $e) {
    if(WP_DEBUG) {
        die($e->getMessage());
    }
}