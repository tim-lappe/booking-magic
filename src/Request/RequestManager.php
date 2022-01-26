<?php

namespace TLBM\Request;

use TLBM\Request\Contracts\RequestManagerInterface;

class RequestManager implements RequestManagerInterface
{
    /**
     * @var array
     */
    private array $registeredEndpoints = array();

    /**
     * @return void
     */
    public function init()
    {
        if (isset($_REQUEST['action'])) {
            $action = $_REQUEST['action'];
            $vars   = $_REQUEST;
            unset($vars['action']);

            $request = $this->getRequest($action);
            if ($request != null) {
                $request->onAction($vars);
            }
        }
    }


    /**
     * @param object $request
     *
     * @return void
     */
    public function registerEndpoint(object $request)
    {
        if(!isset($this->registeredEndpoints[get_class($request)]) && $request instanceof RequestBase) {
            $this->registeredEndpoints[get_class($request)] = $request;
        }
    }

    public function beforeInit()
    {
        if (isset($_REQUEST['action'])) {
            $action  = $_REQUEST['action'];
            $request = $this->getRequest($action);
            if ($request != null) {
                $request->Init($_REQUEST);
            }
        }
    }

    /**
     * @param string $action
     *
     * @return RequestBase
     */
    public function getRequest(string $action): ?RequestBase
    {
        if(isset($this->registeredEndpoints[$action])) {
            return $this->registeredEndpoints[$action];
        }
        return null;
    }
}