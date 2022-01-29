<?php


namespace TLBM\Database;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Tools\ToolsException;
use Throwable;
use TLBM\Database\Contracts\ORMInterface;

class ORMManager implements ORMInterface
{

    /**
     * @var EntityManager|null
     */
    private ?EntityManager $entityManager;

    /**
     * @var EventManager|null
     */
    private ?EventManager $eventManager;


    /**
     *
     */
    public function __construct()
    {
        try {
            $configuration = Setup::createAnnotationMetadataConfiguration(
                [TLBM_DIR . "/src/Entity"], true, null, null, false
            );

            $connection    = [
                "driver"   => "mysqli",
                "user"     => DB_USER,
                "password" => DB_PASSWORD,
                "dbname"   => DB_NAME,
                'host'     => DB_HOST,
                "charset"  => DB_CHARSET
            ];

            $this->initEvents();
            $this->entityManager = EntityManager::create($connection, $configuration, $this->eventManager);

        } catch (Throwable $error) {
            echo $error->getMessage();
        }
    }

    /**
     * @return void
     */
    private function initEvents()
    {
        global $wpdb;
        $tableprefix = new TablePrefixEvent($wpdb->prefix . "booking_magic_");

        $this->eventManager = new EventManager();
        $this->eventManager->addEventListener(Events::loadClassMetadata, $tableprefix);
    }

    /**
     * @return EventManager|null
     */
    public function getEventManager(): ?EventManager
    {
        return $this->eventManager;
    }

    /**
     * @return void
     * @throws ToolsException
     */
    public function buildSchema(): void
    {
        $mgr    = $this->entityManager;
        $schema = new SchemaTool($mgr);
        $schema->createSchema($mgr->getMetadataFactory()->getAllMetadata());
    }

    /**
     * @return EntityManager|null
     */
    public function getEntityManager(): ?EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @return void
     */
    public function dropSchema(): void
    {
        $schema = new SchemaTool($this->entityManager);
        $schema->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }
}