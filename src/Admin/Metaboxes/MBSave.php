<?php


namespace TLBM\Admin\Metaboxes;


use WP_Post;

class MBSave extends MetaBoxBase
{

    public function GetOnPostTypes(): array
    {
        return array(
            TLBM_PT_BOOKING,
            TLBM_PT_FORMULAR,
            TLBM_PT_RULES,
            TLBM_PT_CALENDAR,
            TLBM_PT_CALENDAR_GROUPS
        );
    }

    public function RegisterMetaBox()
    {
        $this->AddMetaBox("tlbm_save", "Save", 'side');
    }

    public function PrintMetaBox(WP_Post $post)
    {
        global $pagenow;
        ?>
        <div id="submitpost">
            <div id="major-publishing-actions">
                <span class="spinner"></span>
                <input name="original_publish" type="hidden" id="original_publish" value="<?php
                _e("Save changes", TLBM_TEXT_DOMAIN) ?>">
                <?php
                if ($pagenow != 'post-new.php'): ?>
                    <input type="submit" name="save" id="publish" class="button button-primary button-large"
                           value="<?php
                           _e("Save changes", TLBM_TEXT_DOMAIN) ?>">
                <?php
                else: ?>
                    <input type="submit" name="publish" id="publish" class="button button-primary button-large"
                           value="<?php
                           _e("Create", TLBM_TEXT_DOMAIN) ?>">
                <?php
                endif; ?>
            </div>
        </div>
        <?php
    }
}