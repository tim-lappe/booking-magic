<?php

namespace TLBM\Repository;

use Exception;
use Iterator;
use TLBM\Entity\ManageableEntity;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Repository\Contracts\ORMInterface;
use TLBM\Repository\Query\ManageableEntityQuery;

class EntityRepository implements EntityRepositoryInterface
{
    /**
     * @var ORMInterface
     */
    private ORMInterface $repository;

    public function __construct(ORMInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @template T
     *
     * @param class-string<T> $className
     * @param int $id
     *
     * @return T|null
     */
    public function getEntity(string $className, int $id)
    {
        try {
            $mgr  = $this->repository->getEntityManager();
            $entity = $mgr->find($className, $id);
            if ($entity instanceof $className) {
                return $entity;
            }
        } catch (Exception $e) {
            if(WP_DEBUG) {
                echo $e->getMessage();
            }
        }

        return null;
    }

    /**
     * @param ManageableEntity $entity
     *
     * @return bool
     */
    public function deleteEntityPermanently(ManageableEntity $entity): bool
    {
        try {
            $mgr  = $this->repository->getEntityManager();
            $mgr->remove($entity);
            $mgr->flush();
            return true;
        } catch (Exception $e) {
            if(WP_DEBUG) {
                echo $e->getMessage();
            }
        }
        return false;
    }

    /**
     * @param ManageableEntity $entity
     *
     * @return bool
     */
    public function moveEntityToTrash(ManageableEntity $entity): bool
    {
        $entity->setAdministrationStatus(TLBM_ENTITY_ADMINSTATUS_DELETED);
        return $this->saveEntity($entity);
    }

    /**
     * @param ManageableEntity $entity
     *
     * @return bool
     */
    public function restoreEntity(ManageableEntity $entity): bool
    {
        $entity->setAdministrationStatus(TLBM_ENTITY_ADMINSTATUS_ACTIVE);
        return $this->saveEntity($entity);
    }

    /**
     * @param ManageableEntity $entity
     *
     * @return bool
     */
    public function saveEntity(ManageableEntity $entity): bool
    {
        try {
            $mgr = $this->repository->getEntityManager();
            $mgr->persist($entity);
            $mgr->flush();
            return true;
        } catch (Exception $exception) {
            if(WP_DEBUG) {
                echo $exception->getMessage();
            }
        }

        return false;
    }

    /**
     * @param class-string $className
     * @param bool $includeDeleted
     *
     * @return int
     */
    public function getEntityCount(string $className, bool $includeDeleted = false): int
    {
        $query = MainFactory::create(ManageableEntityQuery::class);
        $query->setEntityClass($className);
        $query->setIncludeDeleted($includeDeleted);
        return $query->getResultCount();
    }

    /**
     * @template T
     *
     * @param class-string<T> $className
     * @param int|null $offset
     * @param int|null $limit
     *
     * @return Iterator
     */
    public function getEntites(string $className, ?int $offset = null, ?int $limit = null): Iterator
    {
        $mgr = $this->repository->getEntityManager();
        $queryBuilder  = $mgr->createQueryBuilder();
        $queryBuilder->select("e")->from($className, "e");

        if($offset) {
            $queryBuilder->setFirstResult($offset);
        }

        if($limit) {
            $queryBuilder->setMaxResults($limit);
        }

        $query = $queryBuilder->getQuery();
        try {
            foreach ($query->getResult() as $result) {
                if($result instanceof $className) {

                    /**
                     * @var T $result
                     */
                    yield $result;
                }
            }
        } catch (Exception $e) {
            if(WP_DEBUG) {
                echo $e->getMessage();
            }
        }

        return [];
    }
}