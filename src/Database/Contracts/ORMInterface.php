<?php

namespace TLBM\Database\Contracts;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;

interface ORMInterface
{

    public function getEventManager(): ?EventManager;

    public function getEntityManager(): ?EntityManager;

    public function buildSchema(): void;

    public function dropSchema(): void;
}