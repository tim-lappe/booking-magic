<?php

namespace TLBM\Repository\Contracts;

use TLBM\Entity\ManageableEntity;

interface EntityRepositoryInterface
{
    /**
     * @template T
     *
     * @param class-string<T> $className
     * @param int $id
     *
     * @return T|null
     */
    public function getEntity(string $className, int $id);

    /**
     * @param ManageableEntity $entity
     *
     * @return bool
     */
    public function saveEntity(ManageableEntity $entity): bool;

    /**
     * @param class-string $className
     *
     * @return int
     */
    public function getEntityCount(string $className): int;
}