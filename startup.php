<?php


use TLBM\Admin\FormEditor\FormElementsCollection;
use TLBM\AdminPages;
use TLBM\Ajax;
use TLBM\EnqueueAssets;
use TLBM\Metaboxes;
use TLBM\Output\Calendar\CalendarOutput;
use TLBM\RegisterPostTypes;
use TLBM\RegisterShortcodes;
use TLBM\Request;
use TLBM\Settings;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

/**
 * Make Instances of Important Classes
 */
TLBookingMagic::MakeInstance(RegisterPostTypes::class);
TLBookingMagic::MakeInstance(RegisterShortcodes::class);
TLBookingMagic::MakeInstance(EnqueueAssets::class);
TLBookingMagic::MakeInstance(Metaboxes::class);
TLBookingMagic::MakeInstance(Ajax::class);

$GLOBALS['TLBM_REQUEST'] = TLBookingMagic::MakeInstance(Request::class);

TLBookingMagic::MakeInstance(Settings::class);
TLBookingMagic::MakeInstance(AdminPages::class);
/**
 * Register all FormElements for the Formeditor
 */
FormElementsCollection::RegisterFormElements();

/**
 * Register all Calendar Output Printers
 */
CalendarOutput::RegisterCalendarPrinters();