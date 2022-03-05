<?php

namespace TLBM\Entity;

use TLBM\ApiUtils\Contracts\TimeUtilsInterface;
use TLBM\MainFactory;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="cache")
 */
class CacheEntity
{
    /**
     * @var ?string
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false)
     */
    protected ?string $hash = null;

    /**
     * @var ?string
     * @Doctrine\ORM\Mapping\Column(type="text", nullable=false)
     */
    protected ?string $serializedDataString = null;

    /**
     * @var ?int
     * @Doctrine\ORM\Mapping\Column(type="bigint", nullable=false)
     */
    protected ?int $timestampCreated = null;

    /**
     * @var ?int
     * @Doctrine\ORM\Mapping\Column(type="bigint", nullable=true)
     */
    protected ?int $lifetime = null;

    public function __construct()
    {
        $timeUtils = MainFactory::get(TimeUtilsInterface::class);
        $this->timestampCreated = $timeUtils->time();
    }

    /**
     * @return string|null
     */
    public function getSerializedDataString(): ?string
    {
        return $this->serializedDataString;
    }

    /**
     * @param string|null $serializedDataString
     */
    public function setSerializedDataString(?string $serializedDataString): void
    {
        $this->serializedDataString = $serializedDataString;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return unserialize($this->getSerializedDataString());
    }

    /**
     * @param mixed $data
     *
     * @return void
     */
    public function setData($data)
    {
        $this->setSerializedDataString(serialize($data));
    }

    /**
     * @return string|null
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * @param string|null $hash
     */
    public function setHash(?string $hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @return int|null
     */
    public function getLifetime(): ?int
    {
        return $this->lifetime;
    }

    /**
     * @param int|null $lifetime
     */
    public function setLifetime(?int $lifetime): void
    {
        $this->lifetime = $lifetime;
    }
}