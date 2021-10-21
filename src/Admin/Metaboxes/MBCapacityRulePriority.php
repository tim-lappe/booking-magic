<?php


namespace TLBM\Admin\Metaboxes;


use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Admin\WpForm\InputField;
use TLBM\Admin\WpForm\SelectField;
use WP_Post;

if (!defined('ABSPATH')) {
    return;
}

class MBCapacityRulePriority extends MetaBoxForm {

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
        $this->AddMetaBox("calendar_rules_priority", "Priority", "side");
    }

    /**
     * @inheritDoc
     */
    function PrintMetaBox(WP_Post $post) {
        $priority = get_post_meta($post->ID, "priority", true);
        if(!$priority) {
            $priority = 10;
        }

        $form_builder = new FormBuilder();
        $form_builder->PrintFormHead();
        $form_builder->PrintFormField(new InputField("priority","number", __("Priority", TLBM_TEXT_DOMAIN), $priority));
        $form_builder->PrintFormFooter();
    }

    /**
     * @inheritDoc
     */
    function OnSave($post_id) {
    	if(isset($_REQUEST['priority'])) {
		    $priority = $_REQUEST['priority'];
		    update_post_meta( $post_id, "priority", $priority );
	    }
    }
}