<?php

namespace TLBM\Ajax;

use TLBM\Ajax\Contracts\AjaxFunctionInterface;
use TLBM\Ajax\Contracts\AjaxManagerInterface;
use TLBM\ApiUtils\Contracts\HooksInterface;
use TLBM\ApiUtils\Contracts\OptionsInterface;

class AjaxManager implements AjaxManagerInterface
{
    /**
     * @var AjaxFunctionInterface[]
     */
    private array $ajaxFunctions = array();

    /**
     * @var HooksInterface
     */
    private HooksInterface $hooks;

    /**
     *
     */
    public function __construct(HooksInterface $hooks)
    {
        $this->hooks = $hooks;
    }

    /**
     * @param AjaxFunctionInterface $ajaxFunction
     *
     * @return bool
     */
    public function registerAjaxFunction(AjaxFunctionInterface $ajaxFunction): bool
    {
        if(!isset($this->ajaxFunctions[get_class($ajaxFunction)])) {
            $this->ajaxFunctions[get_class($ajaxFunction)] = $ajaxFunction;
            return true;
        }
        return false;
    }

    /**
     * @param string $action
     *
     * @return AjaxFunctionInterface|null
     */
    public function getAjaxFunction(string $action): ?AjaxFunctionInterface
    {
        foreach ($this->ajaxFunctions as $function) {
            if($function->getFunctionName() == $action) {
                return $function;
            }
        }

        return null;
    }

    /**
     * @return AjaxFunctionInterface[]
     */
    public function getAllAjaxFunctions(): array
    {
        return $this->ajaxFunctions;
    }

    /**
     * Helper Function to Register an Ajax Action
     *
     */
    public function initMainAjaxFunction()
    {
        $this->hooks->addAction("wp_ajax_tlbm_ajax", [$this, "executeMainAjaxFunction"]);
        $this->hooks->addAction("wp_ajax_nopriv_tlbm_ajax", [$this, "executeMainAjaxFunction"]);
    }

    /**
     * @return void
     */
    public function executeMainAjaxFunction()
    {
        if (WP_DEBUG) {
            error_reporting(E_ALL);
            ini_set("display_errors", 1);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if($data != null && isset($data['actions'])) {
            if(is_array($data['actions'])) {
                $results = array();
                foreach ($data['actions'] as $singleAction) {
                    $ajaxFunction = $this->getAjaxFunction($singleAction);
                    $payload = $data['payload'][$singleAction] ?? null;
                    $results[$singleAction] = $ajaxFunction->execute($payload);
                }
                wp_send_json($results);
            }
        }
    }
}