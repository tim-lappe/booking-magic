<?php

namespace TLBMTEST\CMS;

use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Throwable;
use TLBM\Repository\Contracts\ORMInterface;
use TLBM\Repository\Functions\Date;
use TLBM\Repository\TablePrefixEvent;

class RepositoryTestWrapper implements ORMInterface
{

    /**
     * @var EntityManager|null
     */
    private ?EntityManager $entityManager;

    /**
     * @var EventManager|null
     */
    private ?EventManager $eventManager;

    public function __construct()
    {
        try {
            $configuration = Setup::createAnnotationMetadataConfiguration([TLBM_DIR . "/src/Entity"], true, null, null, false);
            $this->addCustomFunctions($configuration);

            $connection    = [
                "driver"   => "pdo_sqlite",
                "memory"   => true
            ];

            $this->eventManager = new EventManager();

            $this->initEvents();
            $this->entityManager = EntityManager::create($connection, $configuration, $this->eventManager);

        } catch (Throwable $error) {
            //echo $error->getMessage();
        }
    }

    /**
     * @return EventManager|null
     */
    public function getEventManager(): ?EventManager
    {
        return $this->eventManager;
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
     * @return EntityManagerInterface|EntityManager|null
     */
    public function getEntityManager(): ?EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @return void
     */
    private function initEvents()
    {
        $tableprefix = new TablePrefixEvent("wp_booking_magic_");
        $this->eventManager->addEventListener(Events::loadClassMetadata, $tableprefix);
    }

    /**
     * @return void
     */
    public function buildSchema(): void
    {
        try {
            $mgr    = $this->entityManager;
            $schema = new SchemaTool($mgr);
            $schema->createSchema($mgr->getMetadataFactory()->getAllMetadata());
        } catch (Throwable $throwable) {
            echo $throwable->getMessage();
        }
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