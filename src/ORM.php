<?php


namespace TLBM;


use TLBM\Database\OrmManager;

class ORM {

	public function __construct() {
		OrmManager::Init();
	}
}