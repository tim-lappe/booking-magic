<?php


namespace TLBM\Ajax;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

abstract class AjaxBase {

	public function __construct() {
		$this->RegisterAjaxAction();
	}

	/**
	 * Abstract function that will register the Ajax Action
	 *
	 * @return mixed
	 */
	abstract function RegisterAjaxAction();

    /**
     * Ajax Callback
     *
     * @param $data
     *
     * @return mixed
     */
	abstract function ApiRequest($data);

	function AjaxCallback() {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->ApiRequest($data);
    }

	/**
	 * Helper Function to Register an Ajax Action
	 *
	 * @param $action
	 */
	protected function AddAjaxAction($action) {
		add_action("wp_ajax_" . $action, array($this, "AjaxCallback"));
		add_action("wp_ajax_nopriv_" . $action, array($this, "AjaxCallback"));
	}
}