<?php


namespace TL_Booking\Admin\Metaboxes;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

use WP_Post;

abstract class MetaBoxForm extends MetaBoxBase {


	public function __construct() {
		parent::__construct();
		add_action("save_post", array($this, "SavePostAction" ), 10, 3);
	}

    /**
     * @param int $post_id
     */
	public function SavePostAction(int $post_id) {
		$post_type = get_post_type($post_id);
		if(in_array($post_type, $this->GetOnPostTypes())) {
			remove_action( 'save_post', array($this, "SavePostAction" ));

			$this->OnSave($post_id);

			add_action("save_post", array($this, "SavePostAction" ), 10, 3);
		}
	}

	/**
	 * @param $post_id
	 *
	 */
	abstract function OnSave($post_id);
}