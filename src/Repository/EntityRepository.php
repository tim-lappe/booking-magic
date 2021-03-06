<?php

namespace TLBM\Repository;

use Exception;
use Iterator;
use Throwable;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\TimeUtilsInterface;
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

    /**
     * @var TimeUtilsInterface
     */
    private TimeUtilsInterface $timeUtils;

    /**
     * @var CacheManager
     */
    private CacheManager $cacheManager;

	/**
	 * @var EscapingInterface
	 */
	private EscapingInterface $escaping;

	/**
	 * @param EscapingInterface $escaping
	 * @param ORMInterface $repository
	 * @param TimeUtilsInterface $timeUtils
	 * @param CacheManager $cacheManager
	 */
    public function __construct(EscapingInterface $escaping, ORMInterface $repository, TimeUtilsInterface $timeUtils, CacheManager $cacheManager)
    {
		$this->escaping     = $escaping;
        $this->repository   = $repository;
        $this->timeUtils    = $timeUtils;
        $this->cacheManager = $cacheManager;
    }

    /**
     * @template T
     *
     * @param class-string<T> $className
     * @param int $id
     *
     * @return ?T
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
                die($this->escaping->escHtml(substr($e->getMessage(), 0, 1000)));
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
            $mgr = $this->repository->getEntityManager();
            $mgr->remove($entity);
            $mgr->flush();

            $this->cacheManager->clearCache();

            return true;
        } catch (Exception $e) {
            if(WP_DEBUG) {
                die($this->escaping->escHtml(substr($e->getMessage(), 0, 1000)));
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
        $this->cacheManager->clearCache();

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
        $this->cacheManager->clearCache();

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
            if ( !$mgr->contains($entity)) {
                $entity->setTimestampCreated($this->timeUtils->time());
            }

            $entity->setTimestampEdited($this->timeUtils->time());

            $mgr->persist($entity);
            $mgr->flush();


            $this->cacheManager->clearCache();

            return true;
        } catch ( Throwable $e) {
            if(WP_DEBUG) {
                die($this->escaping->escHtml(substr($e->getMessage(), 0, 1000)));
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
     * @return Iterator<T>
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
                die($this->escaping->escHtml(substr($e->getMessage(), 0, 1000)));
            }
        }

        return [];
    }
}