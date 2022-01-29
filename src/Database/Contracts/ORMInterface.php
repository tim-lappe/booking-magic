<?php

namespace TLBM\Database\Contracts;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;

interface ORMInterface
{

    /**
     * @return EventManager|null
     */
    public function getEventManager(): ?EventManager;

    /**
     * @return EntityManager|null
     */
    public function getEntityManager(): ?EntityManager;

    /**
     * @return void
     */
    public function buildSchema(): void;

    /**
     * @return void
     */
    public function dropSchema(): void;
}