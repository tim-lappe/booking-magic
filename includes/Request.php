<?php


namespace TL_Booking;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use TL_Booking\Request\DoBookingRequest;
use TL_Booking\Request\RequestBase;
use TL_Booking\Request\ShowBookingOverview;

class Request {

	/**
	 * @var RequestBase
	 */
	public $current_action = null;

	public $registered_endpoints = array();

    public function __construct() {
        add_action("init", array($this, "Init"));

        $this->registered_endpoints = array(
	        new DoBookingRequest(),
	        new ShowBookingOverview()
        );

        $this->BeforeInit();
    }

    public function Init() {
        if(isset($_REQUEST['action'])) {
            $action = $_REQUEST['action'];
            $vars = $_REQUEST;
            unset($vars['action']);

            $request = $this->GetRequest($action);
            if($request != null) {
                $request->OnAction($vars);
            }
        }
    }

    public function BeforeInit() {
        if(isset($_REQUEST['action'])) {
            $action = $_REQUEST['action'];
            $request = $this->GetRequest($action);
            if($request != null) {
                $request->Init( $_REQUEST );

                $this->current_action = $request;
            }
        }
    }

    /**
     * @param $action
     *
     * @return false|DoBookingRequest|RequestBase
     */
    public function GetRequest($action) {
        $eps = $this->registered_endpoints;
        foreach ($eps as $ep) {
            if($ep->action == $action) {
                return $ep;
            }
        }

        return false;
    }
}