<?php


namespace TLBM;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TLBM\Admin\Metaboxes\MBBookingActions;
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
use TLBM\Admin\Metaboxes\MBGroupBookingOrder;
use TLBM\Admin\Metaboxes\MBGroupCalendars;
use TLBM\Admin\Metaboxes\MBRuleActions;
use TLBM\Admin\Metaboxes\MBSave;
use TLBookingMagic;

class Metaboxes {

    public function __construct() {
        $this->EnqueueMetaboxes();

	    add_action( 'do_meta_boxes', array($this, "RemoveDefaultPublishBox"));
    }

    public function EnqueueMetaboxes() {
    	TLBookingMagic::MakeInstance(MBSave::class);

        TLBookingMagic::MakeInstance(MBCalendarPreview::class);
        TLBookingMagic::MakeInstance(MBCalendarRules::class);

        TLBookingMagic::MakeInstance(MBCapacityRuleCalendars::class);
        TLBookingMagic::MakeInstance(MBRuleActions::class);
        TLBookingMagic::MakeInstance(MBCapacityRulePeriods::class);
        TLBookingMagic::MakeInstance(MBCapacityRulePriority::class);

        TLBookingMagic::MakeInstance(MBFormEditor::class);
        TLBookingMagic::MakeInstance(MBFormSideInfo::class);

        TLBookingMagic::MakeInstance(MBBookingInformations::class);
	    TLBookingMagic::MakeInstance(MBBookingActions::class);

	    TLBookingMagic::MakeInstance(MBGroupCalendars::class);
	    TLBookingMagic::MakeInstance(MBGroupBookingOrder::class);
    }

    public function RemoveDefaultPublishBox() {
    	remove_meta_box( 'submitdiv', TLBM_PT_CALENDAR, 'side' );
	    remove_meta_box( 'submitdiv', TLBM_PT_RULES, 'side' );
	    remove_meta_box( 'submitdiv', TLBM_PT_FORMULAR, 'side' );
	    remove_meta_box( 'submitdiv', TLBM_PT_BOOKING, 'side' );
	    remove_meta_box( 'submitdiv', TLBM_PT_CALENDAR_GROUPS, 'side' );
    }
}