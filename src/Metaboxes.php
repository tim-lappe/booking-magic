<?php


namespace TLBM;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TLBM\Admin\Metaboxes\MBBookingActions;
use TLBM\Admin\Metaboxes\MBBookingInformations;
use TLBM\Admin\Metaboxes\MBCalendarPreview;
use TLBM\Admin\Metaboxes\MBCalendarRules;
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
    	new MBSave();

        new MBCalendarPreview();
        new MBCalendarRules();

        new MBCapacityRuleCalendars();
        new MBRuleActions();
        new MBCapacityRulePeriods();
        new MBCapacityRulePriority();

        new MBFormEditor();
        new MBFormSideInfo();

        new MBBookingInformations();
	    new MBBookingActions();

	    new MBGroupCalendars();
	    new MBGroupBookingOrder();
    }

    public function RemoveDefaultPublishBox() {
    	remove_meta_box( 'submitdiv', TLBM_PT_CALENDAR, 'side' );
	    remove_meta_box( 'submitdiv', TLBM_PT_RULES, 'side' );
	    remove_meta_box( 'submitdiv', TLBM_PT_FORMULAR, 'side' );
	    remove_meta_box( 'submitdiv', TLBM_PT_BOOKING, 'side' );
	    remove_meta_box( 'submitdiv', TLBM_PT_CALENDAR_GROUPS, 'side' );
    }
}