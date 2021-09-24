<?php


namespace TL_Booking\Admin\Metaboxes;

use WP_Post;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

abstract class MetaBoxBase {

	public function __construct() {
		add_action("add_meta_boxes", array($this, "RegisterMetaBox"));
	}

	/**
	 * Function that soul'd return an array of Post Types
	 *
	 * @return array
	 */
	abstract function GetOnPostTypes(): array;

	/**
	 * Abstract function that will be connected to the add_meta_boxes hook
	 *
	 */
	abstract function RegisterMetaBox();

    /**
     * The Meta Box Callback
     *
     * @param WP_Post $post
     *
     * @return mixed
     */
	abstract function PrintMetaBox(WP_Post $post);

    /**
     * Helper Function to register MetaBoxes for child-classes
     *
     * @param        $slug
     * @param        $title
     * @param string $context
     */
	protected function AddMetaBox($slug, $title, $context = 'normal') {
		add_meta_box(TLBM_MB_PREFIX . $slug, __($title, TLBM_TEXT_DOMAIN), array($this, "PrintMetaBox"), $this->GetOnPostTypes(), $context );
	}
}