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

    public function __construct(ORMInterface $repository, PluginActivationInterface $pluginActivation)
    {
        $this->repository = $repository;

        $pluginActivation->registerActivationHook(TLBM_PLUGIN_FILE, array($this, "onActivation"));
        $pluginActivation->registerDeactivationHook(TLBM_PLUGIN_FILE, array($this, "onDeactivation"));
    }

    public function onActivation() {
		if(file_exists(sys_get_temp_dir () . "/booking-magic-tmp/CompiledContainer.php")) {
			unlink(sys_get_temp_dir () . "/booking-magic-tmp/CompiledContainer.php");
		} else {
			mkdir(sys_get_temp_dir () . "/booking-magic-tmp");
		}

        $this->repository->buildSchema();
    }

    public function onDeactivation() {
        if(TLBM_DELETE_DATA_ON_DEACTIVATION) {
            $this->repository->dropSchema();
        }
    }
}