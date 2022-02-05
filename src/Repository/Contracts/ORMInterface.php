<?php

namespace TLBM\Repository\Contracts;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

interface ORMInterface
{

    /**
     * @return EventManager|null
     */
    public function getEventManager(): ?EventManager;

    /**
     * @return EntityManager|null
     */
    public function getEntityManager(): ?EntityManagerInterface;

    /**
     * @return void
     */
    public function buildSchema(): void;

    /**
     * @return void
     */
    public function dropSchema(): void;
}