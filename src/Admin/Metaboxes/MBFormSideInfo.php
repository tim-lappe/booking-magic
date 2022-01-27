<?php


namespace TLBM\Admin\Metaboxes;

if ( !defined('ABSPATH')) {
    return;
}

use WP_Post;

class MBFormSideInfo extends MetaBoxForm
{

    /**
     * @inheritDoc
     */
    public function GetOnPostTypes(): array
    {
        return array(TLBM_PT_FORMULAR);
    }

    /**
     * @inheritDoc
     */
    public function RegisterMetaBox()
    {
        $this->AddMetaBox("form_side_info", "Display Form", "side");
    }

    /**
     * @inheritDoc
     */
    public function PrintMetaBox(WP_Post $post)
    {
        ?>
        <p>
            <?php
            echo __("To Show this formular on any page or post, you can use this shortcode: ", TLBM_TEXT_DOMAIN) ?>
        </p>
        <p class="tlbm-shortcode-label">
            [<?php
            echo TLBM_SHORTCODETAG_FORM ?> id=<?php
            echo $post->ID ?>]
        </p>
        <?php
    }

    /**
     * @param $post_id
     *
     */
    public function OnSave($post_id)
    {
        if (isset($_REQUEST['show_on_page_id'])) {
            $show_on_page_id = $_REQUEST['show_on_page_id'];
            if (strlen($show_on_page_id) > 0 && is_numeric($show_on_page_id)) {
                update_post_meta($post_id, "show_on_page_id", intval($show_on_page_id));
            } else {
                update_post_meta($post_id, "show_on_page_id", "");
            }
        }
    }
}