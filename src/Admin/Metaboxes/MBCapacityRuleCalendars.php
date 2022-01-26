<?php


namespace TLBM\Admin\Metaboxes;

if ( ! defined('ABSPATH')) {
    return;
}

use TLBM\Admin\WpForm\CalendarPickerField;
use TLBM\Admin\WpForm\FormBuilder;
use WP_Post;

class MBCapacityRuleCalendars extends MetaBoxForm
{

    /**
     * @return array
     */
    public function GetOnPostTypes(): array
    {
        return array(TLBM_PT_RULES);
    }

    /**
     * @return mixed
     */
    public function RegisterMetaBox()
    {
        $this->AddMetaBox("capacity_rule_calendars", "Calendars");
    }

    /**
     * @param WP_Post $post
     */
    public function PrintMetaBox(WP_Post $post)
    {
        $form_builder = new FormBuilder();

        $calendar_selection = get_post_meta($post->ID, "calendar_selection", true);

        $form_builder->displayFormHead();
        $form_builder->displayFormField(
            new CalendarPickerField("calendars", __("Calendars", TLBM_TEXT_DOMAIN), $calendar_selection)
        );
        $form_builder->displayFormFooter();
    }

    /**
     * @param $post_id
     *
     */
    public function OnSave($post_id)
    {
        $calendar_selection = CalendarPickerField::GetCalendarSelectionFromRequest("calendars", $_REQUEST);
        if ($calendar_selection) {
            update_post_meta($post_id, "calendar_selection", $calendar_selection);
        }
    }
}