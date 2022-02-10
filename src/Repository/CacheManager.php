<?php

namespace TLBM\Repository;

use Exception;
use InvalidArgumentException;
use TLBM\Entity\CacheEntity;
use TLBM\Repository\Contracts\CacheManagerInterface;
use TLBM\Repository\Contracts\ORMInterface;

class CacheManager implements CacheManagerInterface
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
     * @return bool
     */
    public function clearCache(): bool
    {
        try {
            $mgr = $this->repository->getEntityManager();
            $queryBuilder = $mgr->createQueryBuilder();
            $queryBuilder->delete(CacheEntity::class);
            $queryBuilder->getQuery()->execute();

            return true;
        } catch (Exception $exception) {
            if(WP_DEBUG) {
                echo $exception->getMessage();
            }
        }
        return false;
    }

    /**
     * @param mixed $hash
     *
     * @return mixed
     */
    public function getData($hash)
    {
        $hash = $this->hashObj($hash);

        try {
            $mgr = $this->repository->getEntityManager();
            $cacheEntity = $mgr->find(CacheEntity::class, $hash);

            if($cacheEntity instanceof CacheEntity) {
                return $cacheEntity->getData();
            }
        } catch (Exception $exception) {
            if(WP_DEBUG) {
                echo $exception->getMessage();
            }
        }

        return null;
    }

    /**
     * @param mixed $hash
     * @param mixed $data
     *
     * @return bool
     */
    public function setData($hash, $data): bool
    {
        try {
            $hashString  = $this->hashObj($hash);
            $cacheEntity = new CacheEntity();
            $cacheEntity->setHash($hashString);
            $cacheEntity->setData($data);

            $mgr = $this->repository->getEntityManager();
            $mgr->persist($cacheEntity);
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
     * @param mixed $hashObj
     *
     * @return string
     */
    private function hashObj($hashObj): string
    {
        if($hashObj != null) {
            return md5(serialize($hashObj));
        }

        throw new InvalidArgumentException("hashObj cannot be null");
    }
}