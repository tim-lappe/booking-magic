<?php


namespace TLBM;

use TLBM\ApiUtils\Contracts\PluginActivationInterface;
use TLBM\Repository\Contracts\ORMInterface;

class PluginActivation
{

    /**
     * @var ORMInterface
     */
    private ORMInterface $repository;

	/**
	 * @param ORMInterface $repository
	 * @param PluginActivationInterface $pluginActivation
	 */
    public function __construct(ORMInterface $repository, PluginActivationInterface $pluginActivation)
    {
        $this->repository = $repository;

        $pluginActivation->registerActivationHook(TLBM_PLUGIN_FILE, array($this, "onActivation"));
        $pluginActivation->registerDeactivationHook(TLBM_PLUGIN_FILE, array($this, "onDeactivation"));
    }

	/**
	 * @return void
	 */
    public function onActivation() {
        $this->repository->buildSchema();
    }

    public function onDeactivation() {
        if(TLBM_DELETE_DATA_ON_DEACTIVATION) {
            $this->repository->dropSchema();
        }
    }
}