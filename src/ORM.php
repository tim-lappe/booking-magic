<?php


namespace TLBM;


use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Tools\Setup;
use TLBM\Database\OrmManager;
use TLBM\Database\TablePrefixEvent;

class ORM {

	public function __construct() {
		OrmManager::Init();
	}
}