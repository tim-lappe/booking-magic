<?php


namespace TL_Booking;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TL_Booking\Admin\Metaboxes\MBBookingCalendarSlots;
use TL_Booking\Admin\Metaboxes\MBBookingDebug;
use TL_Booking\Admin\Metaboxes\MBBookingFormValues;
use TL_Booking\Admin\Metaboxes\MBCalendarPreview;
use TL_Booking\Admin\Metaboxes\MBCalendarRules;
use TL_Booking\Admin\Metaboxes\MBCalendarSetup;
use TL_Booking\Admin\Metaboxes\MBCapacityRuleCalendars;
use TL_Booking\Admin\Metaboxes\MBCapacityRulePeriods;
use TL_Booking\Admin\Metaboxes\MBCapacityRulePriority;
use TL_Booking\Admin\Metaboxes\MBFormEditor;
use TL_Booking\Admin\Metaboxes\MBFormSideInfo;
use TL_Booking\Admin\Metaboxes\MBRuleActions;
use TL_Booking_Magic;

class Metaboxes {

    public function __construct() {
        $this->EnqueueMetaboxes();
    }

    public function EnqueueMetaboxes() {
        TL_Booking_Magic::MakeInstance(MBCalendarPreview::class);
        TL_Booking_Magic::MakeInstance(MBCalendarSetup::class);
        TL_Booking_Magic::MakeInstance(MBCalendarRules::class);

        TL_Booking_Magic::MakeInstance(MBCapacityRuleCalendars::class);
        TL_Booking_Magic::MakeInstance(MBRuleActions::class);
        TL_Booking_Magic::MakeInstance(MBCapacityRulePeriods::class);
        TL_Booking_Magic::MakeInstance(MBCapacityRulePriority::class);

        TL_Booking_Magic::MakeInstance(MBFormEditor::class);
        TL_Booking_Magic::MakeInstance(MBFormSideInfo::class);

        TL_Booking_Magic::MakeInstance(MBBookingFormValues::class);
        TL_Booking_Magic::MakeInstance(MBBookingCalendarSlots::class);
	    TL_Booking_Magic::MakeInstance(MBBookingDebug::class);
    }
}