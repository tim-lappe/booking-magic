<?php

namespace TLBM\Request;

use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\SanitizingInterface;
use TLBM\Request\Contracts\RequestManagerInterface;

class RequestManager implements RequestManagerInterface
{
    /**
     * @var RequestBase[]
     */
    private array $registeredEndpoints = array();

    /**
     * @var RequestBase|null
     */
    private ?RequestBase $currentRequest = null;

	/**
	 * @var EscapingInterface
	 */
	protected EscapingInterface $escaping;

	/**
	 * @var SanitizingInterface
	 */
	protected SanitizingInterface $sanitizing;

	/**
	 * @param SanitizingInterface $sanitizing
	 * @param EscapingInterface $escaping
	 */
	public function __construct(SanitizingInterface $sanitizing, EscapingInterface $escaping)
	{
		$this->escaping = $escaping;
		$this->sanitizing = $sanitizing;
	}

	/**
     * @return void
     */
    public function init()
    {
        if (isset($_REQUEST['tlbm_action'])) {
            $request = $this->getEndpointByAction($this->sanitizing->sanitizeKey($_REQUEST['tlbm_action']));
            if ($request != null) {
                $request->setVars($this->getVars());
                $request->onAction();
            }
        }
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        if($this->hasContent()) {
            return $this->currentRequest->getContent();
        }
        return "";
    }

    /**
     * @return bool
     */
    public function hasContent(): bool
    {
        if($this->currentRequest != null) {
            return $this->currentRequest->hasContent;
        }
        return false;
    }

    /**
     * @param string $action
     *
     * @return RequestBase
     */
    public function getEndpointByAction(string $action): ?RequestBase
    {
        foreach ($this->registeredEndpoints as $endpoint) {
            if($endpoint->action == $action) {
                return $endpoint;
            }
        }

        return null;
    }

    /**
     * @param object $request
     *
     * @return void
     */
    public function registerEndpoint(object $request)
    {
        if ( !isset($this->registeredEndpoints[get_class($request)]) && $request instanceof RequestBase) {
            $this->registeredEndpoints[get_class($request)] = $request;
        }
    }

    public function beforeInit()
    {
        if (isset($_REQUEST['tlbm_action'])) {
            $action  = $this->sanitizing->sanitizeKey($_REQUEST['tlbm_action']);
            $request = $this->getEndpointByAction($action);
            if ($request != null) {
                $this->currentRequest = $request;
            }
        }
    }

    /**
     * @return RequestBase|null
     */
    public function getCurrentRequest(): ?RequestBase
    {
        return $this->currentRequest;
    }

    /**
     * @return array
     */
    public function getVars(): array
    {
        $vars = $_REQUEST;
        if (isset($_REQUEST['tlbm_action'])) {
            unset($vars['tlbm_action']);
        }

        return $vars;
    }
}