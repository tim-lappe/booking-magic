<?php


namespace TLBM\Ajax;

if ( ! defined('ABSPATH')) {
    return;
}

abstract class AjaxBase
{

    public function __construct()
    {
        $this->registerAjaxAction();
    }

    /**
     * Abstract function that will register the Ajax Action
     *
     * @return mixed
     */
    public abstract function registerAjaxAction();

    public function ajaxCallback()
    {
        $data   = json_decode(file_get_contents('php://input'));
        $result = $this->apiRequest($data);
        die(json_encode($result));
    }

    /**
     * Ajax Callback
     *
     * @param $data
     *
     * @return mixed
     */
    public abstract function apiRequest($data);

    /**
     * Helper Function to Register an Ajax Action
     *
     * @param $action
     */
    protected function addAjaxAction($action)
    {
        add_action("wp_ajax_tlbm_" . $action, array($this, "ajaxCallback"));
        add_action("wp_ajax_nopriv_tlbm_" . $action, array($this, "ajaxCallback"));
    }
}