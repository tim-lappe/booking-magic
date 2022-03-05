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
use TLBM\Repository\Contracts\ORMInterface;
use TLBM\Request\Contracts\RequestManagerInterface;

const TLBM_TEST_TIMESTAMP = 1646031163;

try {
    $tlbmContainerBuilder = new ContainerBuilder();
    $tlbmContainerBuilder->addDefinitions ( TLBM_PLUGIN_DIR . "/dependency.php" );
    $tlbmContainerBuilder->addDefinitions ( __DIR__ . "/dependency-overwrite.php" );

    $tlbmContainer = $tlbmContainerBuilder->build ();
    $GLOBALS['TLBM_DICONTAINER'] = $tlbmContainer;

    $tlbmContainer->get ( PluginActivation::class );
    $hooks = $tlbmContainer->get(HooksInterface::class);

    $repository = $tlbmContainer->get(ORMInterface::class);
    $repository->buildSchema();

    $hooks->addAction("init", function () {
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

    $tlbmContainer->get(RegisterShortcodes::class);
    $tlbmContainer->get(EnqueueAssets::class);
    $tlbmContainer->get(FormElementsCollectionInterface::class);

    require_once __DIR__ . "/example-data-insertion.php";

} catch (Throwable $e) {
    echo $e->getMessage() . "\n";
    echo $e->getFile() . "\n";
    echo "Line: " .  $e->getLine() . "\n";
}