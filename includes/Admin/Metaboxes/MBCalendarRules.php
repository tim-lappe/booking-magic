<?php


namespace TL_Booking\Admin\Metaboxes;


use TL_Booking\Admin\Tables\RulesListTable;
use TL_Booking\Rules\RulesManager;
use WP_Post;

if (!defined('ABSPATH')) {
    return;
}

class MBCalendarRules extends MetaBoxBase {

    /**
     * @inheritDoc
     */
    function GetOnPostTypes(): array {
        return array(TLBM_PT_CALENDAR);
    }

    /**
     * @inheritDoc
     */
    function RegisterMetaBox() {
        $this->AddMetaBox("calendar_rules", "Rules");
    }

    /**
     * @inheritDoc
     */
    function PrintMetaBox(WP_Post $post) {
        ?>
        <h3>Rules for this Calendar</h3>
        <?php
        $rules_table = new RulesListTable($post->ID);
        $rules_table->prepare_items();
        $rules_table->display();
        ?>
        <?php
    }
}