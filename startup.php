<?php


use TLBM\Admin\FormEditor\FormElementsCollection;
use TLBM\AdminPages;
use TLBM\Ajax;
use TLBM\Database\OrmManager;
use TLBM\EnqueueAssets;
use TLBM\Metaboxes;
use TLBM\Output\Calendar\CalendarOutput;
use TLBM\PluginActivation;
use TLBM\RegisterPostTypes;
use TLBM\RegisterShortcodes;
use TLBM\Request;
use TLBM\Settings;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

if(WP_DEBUG) {
    error_reporting(E_ALL);
    ini_set ('error_reporting', E_ALL);
    ini_set ('display_errors', true);
    ini_set ('display_startup_errors', true);
    ini_set ("error_log", "/tmp/phplog.txt");
}

OrmManager::Init();
new PluginActivation();


/**
 * Make Instances of Important Classes
 */
if(in_array(TLBM_PLUGIN_RELATIVE_DIR_FILE, apply_filters('active_plugins', get_option('active_plugins')))){
    //plugin is activated

    new RegisterPostTypes();
    new RegisterShortcodes();
    new EnqueueAssets();
    new Metaboxes();
    new Ajax();

    $GLOBALS['TLBM_REQUEST'] = new Request();

    new Settings();
    new AdminPages();

    /**
     * Register all FormElements for the Formeditor
     */
    FormElementsCollection::RegisterFormElements();

    /**
     * Register all Calendar Output Printers
     */
    CalendarOutput::RegisterCalendarPrinters();
}