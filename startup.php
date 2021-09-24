<?php


use TL_Booking\Admin\FormEditor\FormElementsCollection;
use TL_Booking\AdminPages;
use TL_Booking\Ajax;
use TL_Booking\EnqueueAssets;
use TL_Booking\Metaboxes;
use TL_Booking\Output\Calendar\CalendarOutput;
use TL_Booking\RegisterPostTypes;
use TL_Booking\RegisterShortcodes;
use TL_Booking\Request;
use TL_Booking\Settings;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

/**
 * Make Instances of Important Classes
 */
TL_Booking_Magic::MakeInstance(RegisterPostTypes::class);
TL_Booking_Magic::MakeInstance(RegisterShortcodes::class);
TL_Booking_Magic::MakeInstance(EnqueueAssets::class);
TL_Booking_Magic::MakeInstance(Metaboxes::class);
TL_Booking_Magic::MakeInstance(Ajax::class);

$GLOBALS['TLBM_REQUEST'] = TL_Booking_Magic::MakeInstance(Request::class);

TL_Booking_Magic::MakeInstance(Settings::class);
TL_Booking_Magic::MakeInstance(AdminPages::class);
/**
 * Register all FormElements for the Formeditor
 */
FormElementsCollection::RegisterFormElements();

/**
 * Register all Calendar Output Printers
 */
CalendarOutput::RegisterCalendarPrinters();