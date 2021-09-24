<?php


namespace TL_Booking\Model;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

class Form {

	/**
	 * @var int
	 */
	public $wp_post_id;

	/**
	 * @var string
	 */
	public $frontend_html;

	/**
	 * @var mixed
	 */
	public $form_data;

	/**
	 * @var string
	 */
	public $title;

	public function GetFormData() {
	    $fd = $this->form_data;
        $fd = html_entity_decode($fd);
        return json_decode($fd, true);
    }
}