<?php

namespace TLBM\Repository\Contracts;

interface CacheManagerInterface
{
    /**
     * @param mixed $hash
     *
     * @return mixed
     */
    public function getData($hash);

    /**
     * @param mixed $hash
     * @param mixed $data
     *
     * @return bool
     */
    public function setData($hash, $data): bool;


    /**
     * @return bool
     */
    public function clearCache(): bool;

    /**
     * @param mixed $hash
     *
     * @return bool
     */
    public function entryExists($hash): bool;
}