<?php


namespace TLBM\Admin\Metaboxes;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use TLBM\Admin\FormEditor\FrontendGeneration\FormFrontendGenerator;
use WP_Post;

class MBFormEditor extends MetaBoxForm {

	/**
	 * @return array
	 */
	function GetOnPostTypes(): array {
		return array(TLBM_PT_FORMULAR);
	}

	function RegisterMetaBox() {
		$this->AddMetaBox("form_editor", "Editor");
	}

    /**
     * @param WP_Post $post
     */
	function PrintMetaBox(WP_Post $post) {

	    $formdata = get_post_meta($post->ID, "form-data", true);
		?>
		<div class="tlbm-form-editor">
            <div class="tlbm-form-container tlbm-main-form-container">
                <div class="tlbm-draggable-container tlbm-form-dragdrop-container">

                </div>
                <button class="button button-primary-outline tlbm-button-add-element"><?php echo __("Add Element", TLBM_TEXT_DOMAIN); ?></button>
            </div>
            <div class="tlbm-form-editor-select-element-window tlbm-window-outer closed">
                <div class="tlbm-add-elements-window-inner tlbm-window-inner">
                    <button class="button button-danger tlbm-close-button"><?php echo __("Close", TLBM_TEXT_DOMAIN) ?></button>
                    <div class="tlbm-form-elements-list">

                    </div>
                </div>
            </div>
            <div class="tlbm-form-editor-element-settings-window tlbm-window-outer closed">
                <div class="tlbm-element-settings-window-inner tlbm-window-inner">
                    <div class="tlbm-form-settings-container">

                    </div>
                    <button class="button button-primary button-large tlbm-save-button"><?php echo __("Save", TLBM_TEXT_DOMAIN) ?></button>
                    <button class="button button-large tlbm-cancel-button"><?php echo __("Cancel", TLBM_TEXT_DOMAIN) ?></button>
                </div>
            </div>
            <input id="tlbm-form-editor-data" name="tlbm-form-editor-data" type="hidden" value="<?php echo $formdata ?>">
        </div>
		<?php
	}

	/**
	 * @param $post_id
	 *
	 */
	function OnSave( $post_id ) {
	    if(isset($_REQUEST['tlbm-form-editor-data'])) {
		    $form_data = $_REQUEST['tlbm-form-editor-data'];
		    update_post_meta( $post_id, "form-data", $form_data );

		    $form_data     = json_decode( htmlspecialchars_decode( $form_data ) );
		    $ffg           = new FormFrontendGenerator( $form_data );
		    $frontend_html = $ffg->GenerateHtml();

		    update_post_meta( $post_id, "frontend-html", $frontend_html );
	    }
	}
}