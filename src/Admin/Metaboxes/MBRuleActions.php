<?php


namespace TLBM\Admin\Metaboxes;


use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Admin\WpForm\RuleActionsField;
use TLBM\Model\RuleAction;
use TLBM\Model\RuleActionCollection;
use WP_Post;

if ( !defined('ABSPATH')) {
    return;
}

class MBRuleActions extends MetaBoxForm
{

    /**
     * @inheritDoc
     */
    public function GetOnPostTypes(): array
    {
        return array(TLBM_PT_RULES);
    }

    /**
     * @inheritDoc
     */
    public function RegisterMetaBox()
    {
        $this->AddMetaBox("rules_actions", "Actions");
    }

    /**
     * @inheritDoc
     */
    public function PrintMetaBox(WP_Post $post)
    {
        $ruleactioncollection = get_post_meta($post->ID, "actions", true);

        $form_builder = new FormBuilder();
        $form_builder->displayFormHead();
        $form_builder->displayFormField(
            new RuleActionsField("actions", __("Actions", TLBM_TEXT_DOMAIN), $ruleactioncollection)
        );
        $form_builder->displayFormFooter();
    }

    public function OnSave($post_id)
    {
        if (isset($_REQUEST['actions'])) {
            $actions = $_REQUEST['actions'];
            $actions = str_replace("&quot;", "\"", $actions);
            $actions = json_decode($actions, false, 20, JSON_FORCE_OBJECT);

            if (is_array($actions)) {
                $ruleactioncollection = new RuleActionCollection();
                foreach ($actions as $action) {
                    $ruleaction             = new RuleAction();
                    $ruleaction->actiontype = $action->actiontype;
                    $ruleaction->values     = $action->values;

                    $ruleactioncollection->actions_list[] = $ruleaction;
                }

                update_post_meta($post_id, "actions", $ruleactioncollection);
            }
        }
    }
}