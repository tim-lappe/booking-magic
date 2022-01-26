<?php


namespace TLBM\Database;


use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Exception;
use TLBM\Database\Contracts\ORMInterface;

class ORMManager implements ORMInterface
{

    private ?EntityManager $entityManager;
    private ?EventManager $eventManager;

    public function __construct()
    {
        $configuration = Setup::createAnnotationMetadataConfiguration(
            [TLBM_DIR . "/src/Entity"],
            true,
            null,
            null,
            false
        );
        $connection    = array(
            "driver"   => "mysqli",
            "user"     => DB_USER,
            "password" => DB_PASSWORD,
            "dbname"   => DB_NAME,
            'host'     => DB_HOST,
            "charset"  => DB_CHARSET
        );

        try {
            $this->initEvents();
            $this->entityManager = EntityManager::create($connection, $configuration, $this->eventManager);
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    private function initEvents()
    {
        global $wpdb;
        $tableprefix = new TablePrefixEvent($wpdb->prefix . "booking_magic_");

        $this->eventManager = new EventManager();
        $this->eventManager->addEventListener(Events::loadClassMetadata, $tableprefix);
    }

    public function getEventManager(): ?EventManager
    {
        return $this->eventManager;
    }

    public function buildSchema(): void
    {
        try {
            $mgr    = self::GetEntityManager();
            $schema = new SchemaTool($mgr);
            $schema->createSchema($mgr->getMetadataFactory()->getAllMetadata());
        } catch (ORMException $error) {
            var_dump($error);
        }
    }

    public function getEntityManager(): ?EntityManager
    {
        return $this->entityManager;
    }

    public function dropSchema(): void
    {
        $schema = new SchemaTool($this->entityManager);
        $schema->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }
}