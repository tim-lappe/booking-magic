<?php


namespace TLBM\Repository;

use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Throwable;
use TLBM\Repository\Contracts\ORMInterface;
use TLBM\Repository\Functions\Date;

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
            $configuration = Setup::createAnnotationMetadataConfiguration([TLBM_DIR . "/src/Entity"], true, null, null, false);
            $this->addCustomFunctions($configuration);

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
     * @param Configuration $configuration
     *
     * @return void
     */
    public function addCustomFunctions(Configuration $configuration): void
    {
        $configuration->addCustomStringFunction("DATE", Date::class);
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
     */
    public function buildSchema(): void
    {
        $mgr    = $this->entityManager;
        $schema = new SchemaTool($mgr);
        $schema->updateSchema($mgr->getMetadataFactory()->getAllMetadata(), true);
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