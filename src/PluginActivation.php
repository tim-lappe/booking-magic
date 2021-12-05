<?php


namespace TLBM;


use Doctrine\ORM\Tools\SchemaTool;
use TLBM\Database\OrmManager;

class PluginActivation {

	public function __construct() {
		register_activation_hook( TLBM_PLUGIN_FILE, array($this, "OnActivation"));
		register_deactivation_hook( TLBM_PLUGIN_FILE, array($this, "OnDeactivation"));
	}

	public function OnActivation() {
		OrmManager::BuildSchema();
	}

	public function OnDeactivation() {
		if(defined("TLBM_DELETE_DATA_ON_DEACTIVATION")) {
			$mgr    = OrmManager::GetEntityManager();
			$schema = new SchemaTool( $mgr );
			$schema->dropSchema( $mgr->getMetadataFactory()->getAllMetadata() );
		}
	}
}