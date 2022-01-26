<?php


namespace TLBM;

use TLBM\Database\Contracts\ORMInterface;

class PluginActivation
{

    private ORMInterface $repository;

    public function __construct(ORMInterface $repository)
    {
        $this->repository = $repository;

        register_activation_hook(TLBM_PLUGIN_FILE, array($this, "OnActivation"));
        register_deactivation_hook(TLBM_PLUGIN_FILE, array($this, "OnDeactivation"));
    }

    public function OnActivation()
    {
        $this->repository->buildSchema();
    }

    public function OnDeactivation()
    {
        if (defined("TLBM_DELETE_DATA_ON_DEACTIVATION")) {
            $this->repository->dropSchema();
        }
    }
}