<?php


namespace TLBM\Admin\Metaboxes;


use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Admin\WpForm\PeriodEditorField;
use TLBM\Model\PeriodCollection;
use WP_Post;

if ( !defined('ABSPATH')) {
    return;
}

class MBCapacityRulePeriods extends MetaBoxForm
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
        $this->AddMetaBox("periods", "Periods of time");
    }

    /**
     * @inheritDoc
     */
    public function PrintMetaBox(WP_Post $post)
    {
        $collection = get_post_meta($post->ID, "periods", true);
        if ( !($collection instanceof PeriodCollection)) {
            $collection = new PeriodCollection();
        }

        $form_builder = new FormBuilder();
        $form_builder->displayFormHead();
        $form_builder->displayFormField(new PeriodEditorField("periods", __("Periods", TLBM_TEXT_DOMAIN), $collection));
        $form_builder->displayFormFooter();
    }

    public function OnSave($post_id)
    {
        if (isset($_REQUEST['periods'])) {
            $periodData = $_REQUEST['periods'];
            $periodData = str_replace("&quot;", "\"", $periodData);
            $periodData = json_decode($periodData, false, 20, JSON_FORCE_OBJECT);

            $collection              = new PeriodCollection();
            $collection->period_list = $periodData;

            update_post_meta($post_id, "periods", $collection);
        }
    }
}