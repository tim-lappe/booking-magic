<?php


namespace TL_Booking\Request;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


abstract class RequestBase {

    public $action;

    public $html_output = false;

    public function __construct() {

    }

    public function Init( $vars ) { }

    public function OnAction( $vars ) { }

    public function GetHtmlOutput( $vars ) { }
}