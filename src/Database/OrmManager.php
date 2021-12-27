<?php


namespace TLBM\Database;


use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Exception;

class OrmManager {

	private static ?EntityManager $entity_manager;
	private static ?EventManager $event_manager;

	public static function Init() {
		$configuration = Setup::createAnnotationMetadataConfiguration( [ TLBM_DIR . "/src/Entity" ], true, null, null, false);
		$connection = array(
			"driver" => "mysqli",
			"user" => DB_USER,
			"password" => DB_PASSWORD,
			"dbname" => DB_NAME,
			'host' => DB_HOST,
			"charset" => DB_CHARSET
		);

		try {
			self::InitEvents();
			self::$entity_manager = EntityManager::create( $connection, $configuration, self::$event_manager );

        } catch ( Exception $e ) {
			var_dump($e);
		}
	}

	public static function GetEventManager(): ?EventManager {
		return self::$event_manager;
	}

	/**
	 * @return EntityManager
	 */
	public static function GetEntityManager(): ?EntityManager {
		return self::$entity_manager;
	}

	public static function BuildSchema() {
        if(!self::GetEntityManager()) {
            OrmManager::Init();
        }

		try {
			$mgr    = self::GetEntityManager();
			$schema = new SchemaTool( $mgr );
			$schema->createSchema( $mgr->getMetadataFactory()->getAllMetadata() );

		} catch (ORMException $error) {
            var_dump($error);
		}
	}

	private static function InitEvents() {
		global $wpdb;

		self::$event_manager = new EventManager();

		$tableprefix = new TablePrefixEvent($wpdb->prefix . "booking_magic_");
		self::$event_manager->addEventListener(Events::loadClassMetadata, $tableprefix);
	}
}