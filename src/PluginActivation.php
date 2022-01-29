<?php


namespace TLBM;

use TLBM\Database\Contracts\ORMInterface;

class PluginActivation
{

    /**
     * @var ORMInterface
     */
    private ORMInterface $repository;

    public function __construct(ORMInterface $repository)
    {
        $this->repository = $repository;

        register_activation_hook(TLBM_PLUGIN_FILE, array($this, "onActivation"));
        register_deactivation_hook(TLBM_PLUGIN_FILE, array($this, "onDeactivation"));
    }

    public function onActivation() {
        $this->repository->buildSchema();
    }

    public function onDeactivation() {
        $this->repository->dropSchema();
    }
}