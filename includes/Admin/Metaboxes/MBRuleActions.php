<?php


namespace TL_Booking\Admin\Metaboxes;


use TL_Booking\Admin\WpForm\FormBuilder;
use TL_Booking\Admin\WpForm\PeriodEditorField;
use TL_Booking\Admin\WpForm\RuleActionsField;
use TL_Booking\Model\RuleAction;
use TL_Booking\Model\RuleActionCollection;
use WP_Post;

if (!defined('ABSPATH')) {
    return;
}

class MBRuleActions extends MetaBoxForm {

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
        $this->AddMetaBox("rules_actions", "Actions");
    }

    /**
     * @inheritDoc
     */
    function PrintMetaBox(WP_Post $post) {
	    $ruleactioncollection = get_post_meta($post->ID, "actions", true);

        $form_builder = new FormBuilder();
        $form_builder->PrintFormHead();
        $form_builder->PrintFormField(new RuleActionsField("actions",  __("Actions", TLBM_TEXT_DOMAIN), $ruleactioncollection));
        $form_builder->PrintFormFooter();
    }

    function OnSave($post_id) {
    	if(isset($_REQUEST['actions'])) {
		    $actions = $_REQUEST['actions'];
		    $actions = str_replace( "&quot;", "\"", $actions );
		    $actions = json_decode( $actions, false, 20, JSON_FORCE_OBJECT );

		    if ( is_array( $actions ) ) {
			    $ruleactioncollection = new RuleActionCollection();
			    foreach ( $actions as $action ) {
				    $ruleaction             = new RuleAction();
				    $ruleaction->actiontype = $action->actiontype;
				    $ruleaction->values     = $action->values;

				    $ruleactioncollection->actions_list[] = $ruleaction;
			    }

			    update_post_meta( $post_id, "actions", $ruleactioncollection );
		    }
	    }
    }
}