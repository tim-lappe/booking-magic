<?php


namespace TL_Booking\Admin\Metaboxes;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use WP_Post;

class MBFormSideInfo extends MetaBoxForm {

	/**
	 * @inheritDoc
	 */
	function GetOnPostTypes(): array {
		return array(TLBM_PT_FORMULAR);
	}

	/**
	 * @inheritDoc
	 */
	function RegisterMetaBox() {
		$this->AddMetaBox("form_side_info", "Display Form", "side");
	}

	/**
	 * @inheritDoc
	 */
	function PrintMetaBox(WP_Post $post) {
		?>
		<p>
			<?php echo __("To Show this formular on any page or post, you can use this shortcode: ", TLBM_TEXT_DOMAIN) ?>
		</p>
		<p class="tlbm-shortcode-label">
			[<?php echo TLBM_SHORTCODETAG_FORM ?> id=<?php echo $post->ID ?>]
		</p>
		<p>
			<?php echo __("Alternative you can select a Page, to display this formular after the content: ", TLBM_TEXT_DOMAIN) ?>
		</p>
		<select name="show_on_page_id">
			<option value=""><?php echo __("No Page", TLBM_TEXT_DOMAIN) ?></option>
		<?php

		/**
		 * @var WP_Post[] $pages
		 */
		$pages = get_pages();
		$show_on_page_id = get_post_meta($post->ID, "show_on_page_id", true);

		foreach ($pages as $page) {
			?>
			<option <?php selected($show_on_page_id == $page->ID) ?> value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
			<?php
		}
		?>
		</select>
		<?php
	}

	/**
	 * @param $post_id
	 *
	 */
	function OnSave( $post_id ) {
	    if(isset($_REQUEST['show_on_page_id'])) {
		    $show_on_page_id = $_REQUEST['show_on_page_id'];
		    if ( isset( $show_on_page_id ) && strlen( $show_on_page_id ) > 0 && is_numeric( $show_on_page_id ) ) {
			    update_post_meta( $post_id, "show_on_page_id", intval( $show_on_page_id ) );
		    } else {
			    update_post_meta( $post_id, "show_on_page_id", "" );
		    }
	    }
	}
}