<?php


namespace TLBM\Admin\Metaboxes;

if ( ! defined('ABSPATH')) {
    return;
}

use TLBM\Model\CalendarSelection;
use TLBM\Output\Calendar\CalendarOutput;
use WP_Post;


class MBCalendarPreview extends MetaBoxBase
{

    public function RegisterMetaBox()
    {
        $this->AddMetaBox("calendar_preview", "Preview");
    }

    public function PrintMetaBox(WP_Post $post)
    {
        echo CalendarOutput::GetContainerShell($post->ID);
    }

    /**
     * @return mixed
     */
    public function GetOnPostTypes(): array
    {
        return array(TLBM_PT_CALENDAR);
    }
}