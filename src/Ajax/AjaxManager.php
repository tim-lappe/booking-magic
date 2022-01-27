<?php

namespace TLBM\Ajax;

use TLBM\Ajax\Contracts\AjaxFunctionInterface;
use TLBM\Ajax\Contracts\AjaxManagerInterface;

class AjaxManager implements AjaxManagerInterface
{
    /**
     * @var AjaxFunctionInterface[]
     */
    private array $ajaxFunctions = array();


    /**
     * @param AjaxFunctionInterface $ajaxFunction
     *
     * @return bool
     */
    public function registerAjaxFunction(AjaxFunctionInterface $ajaxFunction): bool
    {
        if(!isset($this->ajaxFunctions[get_class($ajaxFunction)])) {
            $this->ajaxFunctions[get_class($ajaxFunction)] = $ajaxFunction;
            $this->addWpAjaxFunction($ajaxFunction->getFunctionName(), function () use ($ajaxFunction) {
                $this->executeAjaxFunction($ajaxFunction);
            });
            return true;
        }
        return false;
    }

    /**
     * @return AjaxFunctionInterface[]
     */
    public function getAllAjaxFunctions(): array
    {
        return $this->ajaxFunctions;
    }

    /**
     * @param AjaxFunctionInterface $ajaxFunction
     *
     * @return void
     */
    public function executeAjaxFunction(AjaxFunctionInterface $ajaxFunction) {
        if (WP_DEBUG) {
            error_reporting(E_ALL);
            ini_set("display_errors", 1);
        }

        $data   = json_decode(file_get_contents('php://input'));
        $result = $ajaxFunction->execute($data);
        die(json_encode($result));
    }

    /**
     * Helper Function to Register an Ajax Action
     *
     * @param string $action_name
     * @param callable $action
     */
    private function addWpAjaxFunction(string $action_name, callable $action)
    {
        add_action("wp_ajax_tlbm_" . $action_name, $action);
        add_action("wp_ajax_nopriv_tlbm_" . $action_name, $action);
    }
}