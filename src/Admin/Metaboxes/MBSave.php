<?php


namespace TLBM\Admin\Metaboxes;


use WP_Post;

class MBSave extends MetaBoxBase  {

	function GetOnPostTypes(): array {
		return array(
			TLBM_PT_BOOKING,
			TLBM_PT_FORMULAR,
			TLBM_PT_RULES,
			TLBM_PT_CALENDAR
		);
	}

	function RegisterMetaBox() {
		$this->AddMetaBox("tlbm_save", "Save", 'side');
	}

	function PrintMetaBox( WP_Post $post ) {
		?>
        <div id="submitpost">
            <div id="major-publishing-actions">
                <span class="spinner"></span>
                <input name="original_publish" type="hidden" id="original_publish" value="<?php _e("Save changes", TLBM_TEXT_DOMAIN) ?>">
                <input type="submit" name="save" id="publish" class="button button-primary button-large" value="<?php _e("Save changes", TLBM_TEXT_DOMAIN) ?>">
            </div>
        </div>
		<?php
	}
}