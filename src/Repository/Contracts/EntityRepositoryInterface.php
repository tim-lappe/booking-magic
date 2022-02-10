<?php

namespace TLBM\Repository\Contracts;

use Exception;
use Iterator;
use TLBM\Entity\ManageableEntity;
use TLBM\Repository\EntityRepository;

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
     * @param bool $includeDeleted
     *
     * @return int
     */
    public function getEntityCount(string $className, bool $includeDeleted = false): int;

    /**
     * @template T
     *
     * @param class-string<T> $className
     * @param int|null $offset
     * @param int|null $limit
     *
     * @return Iterator
     */
    public function getEntites(string $className, ?int $offset = null, ?int $limit = null): Iterator;


    /**
     * @param ManageableEntity $entity
     *
     * @return bool
     */
    public function deleteEntityPermanently(ManageableEntity $entity): bool;

    /**
     * @param ManageableEntity $entity
     *
     * @return bool
     */
    public function restoreEntity(ManageableEntity $entity): bool;


    /**
     * @param ManageableEntity $entity
     *
     * @return bool
     */
    public function moveEntityToTrash(ManageableEntity $entity): bool;

}