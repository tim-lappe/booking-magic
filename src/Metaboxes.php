<?php


namespace TLBM;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TLBM\Admin\Metaboxes\MBBookingActions;
use TLBM\Admin\Metaboxes\MBBookingCalendarSlots;
use TLBM\Admin\Metaboxes\MBBookingDebug;
use TLBM\Admin\Metaboxes\MBBookingInformations;
use TLBM\Admin\Metaboxes\MBCalendarPreview;
use TLBM\Admin\Metaboxes\MBCalendarRules;
use TLBM\Admin\Metaboxes\MBCalendarSetup;
use TLBM\Admin\Metaboxes\MBCapacityRuleCalendars;
use TLBM\Admin\Metaboxes\MBCapacityRulePeriods;
use TLBM\Admin\Metaboxes\MBCapacityRulePriority;
use TLBM\Admin\Metaboxes\MBFormEditor;
use TLBM\Admin\Metaboxes\MBFormSideInfo;
use TLBM\Admin\Metaboxes\MBRuleActions;
use TLBookingMagic;

class Metaboxes {

    public function __construct() {
        $this->EnqueueMetaboxes();
    }

    public function EnqueueMetaboxes() {
        TLBookingMagic::MakeInstance(MBCalendarPreview::class);
    //    TLBookingMagic::MakeInstance(MBCalendarSetup::class);
        TLBookingMagic::MakeInstance(MBCalendarRules::class);

        TLBookingMagic::MakeInstance(MBCapacityRuleCalendars::class);
        TLBookingMagic::MakeInstance(MBRuleActions::class);
        TLBookingMagic::MakeInstance(MBCapacityRulePeriods::class);
        TLBookingMagic::MakeInstance(MBCapacityRulePriority::class);

        TLBookingMagic::MakeInstance(MBFormEditor::class);
        TLBookingMagic::MakeInstance(MBFormSideInfo::class);

        TLBookingMagic::MakeInstance(MBBookingInformations::class);
	    TLBookingMagic::MakeInstance(MBBookingActions::class);
    }
}