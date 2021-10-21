<?php


namespace TLBM\Admin\Metaboxes;


use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Admin\WpForm\PeriodEditorField;
use TLBM\Admin\WpForm\WeekdaysField;
use TLBM\Model\PeriodCollection;
use WP_Post;

if (!defined('ABSPATH')) {
    return;
}

class MBCapacityRulePeriods extends MetaBoxForm {

    /**
     * @inheritDoc
     */
    function GetOnPostTypes(): array {
        return array(TLBM_PT_RULES);
    }

    /**
     * @inheritDoc
     */
    function RegisterMetaBox() {
        $this->AddMetaBox("periods", "Periods of time");
    }

    /**
     * @inheritDoc
     */
    function PrintMetaBox(WP_Post $post) {
	    $collection = get_post_meta($post->ID, "periods", true);
		if(!($collection instanceof PeriodCollection)) {
			$collection = new PeriodCollection();
		}

        $form_builder = new FormBuilder();
        $form_builder->PrintFormHead();
        $form_builder->PrintFormField(new PeriodEditorField("periods",  __("Periods", TLBM_TEXT_DOMAIN), $collection));
        $form_builder->PrintFormFooter();
    }

    function OnSave($post_id) {
    	if(isset($_REQUEST['periods'])) {
		    $periodData = $_REQUEST['periods'];
		    $periodData = str_replace( "&quot;", "\"", $periodData );
		    $periodData = json_decode( $periodData, false, 20, JSON_FORCE_OBJECT );

		    $collection              = new PeriodCollection();
		    $collection->period_list = $periodData;

		    update_post_meta( $post_id, "periods", $collection );
	    }
    }
}