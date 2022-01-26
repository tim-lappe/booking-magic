<?php


namespace TLBM\Admin\Metaboxes;


use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Admin\WpForm\InputField;
use WP_Post;

if ( ! defined('ABSPATH')) {
    return;
}

class MBCapacityRulePriority extends MetaBoxForm
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
        $this->AddMetaBox("calendar_rules_priority", "Priority", "side");
    }

    /**
     * @inheritDoc
     */
    public function PrintMetaBox(WP_Post $post)
    {
        $priority = get_post_meta($post->ID, "priority", true);
        if ( ! $priority) {
            $priority = 10;
        }

        $form_builder = new FormBuilder();
        $form_builder->displayFormHead();
        $form_builder->displayFormField(
            new InputField("priority", "number", __("Priority", TLBM_TEXT_DOMAIN), $priority)
        );
        $form_builder->displayFormFooter();
    }

    /**
     * @inheritDoc
     */
    public function OnSave($post_id)
    {
        if (isset($_REQUEST['priority'])) {
            $priority = $_REQUEST['priority'];
            update_post_meta($post_id, "priority", $priority);
        }
    }
}