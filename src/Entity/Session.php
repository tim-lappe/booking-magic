<?php

namespace TLBM\Entity;


use TLBM\Entity\Traits\IndexedEntity;

/**
 * Class Session
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="sessions")
 */
class Session
{
    use IndexedEntity;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column(type="text", nullable=false)
     */
    protected string $sessionKey;

    /**
     * @var mixed
     * @Doctrine\ORM\Mapping\Column (type="object", nullable=true)
     */
    protected $sessionValue;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="bigint", nullable=false)
     */
    protected int $sessionExpiryTimestamp;

    /**
     * @return int
     */
    public function getSessionExpiryTimestamp(): int
    {
        return $this->sessionExpiryTimestamp;
    }

    /**
     * @param int $sessionExpiryTimestamp
     */
    public function setSessionExpiryTimestamp(int $sessionExpiryTimestamp): void
    {
        $this->sessionExpiryTimestamp = $sessionExpiryTimestamp;
    }

    /**
     * @return mixed
     */
    public function getSessionValue()
    {
        return $this->sessionValue;
    }

    /**
     * @param mixed $sessionValue
     */
    public function setSessionValue($sessionValue): void
    {
        $this->sessionValue = $sessionValue;
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getSingleValue(string $key)
    {
        $valObj = $this->getSessionValue();
        if ($valObj != null) {
            if (isset($valObj[$key])) {
                return $valObj[$key];
            }
        }

        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function setSingleValue(string $key, $value)
    {
        $valObj = $this->getSessionValue();
        if ($valObj == null) {
            $valObj = [];
        }

        $valObj[$key] = $value;
        $this->setSessionValue($valObj);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function removeSingleValue(string $key)
    {
        $valObj = $this->getSessionValue();
        if ($valObj == null) {
            return;
        }

        unset($valObj[$key]);
        $this->setSessionValue($valObj);
    }

    /**
     * @return string
     */
    public function getSessionKey(): string
    {
        return $this->sessionKey;
    }

    /**
     * @param string $sessionKey
     */
    public function setSessionKey(string $sessionKey): void
    {
        $this->sessionKey = $sessionKey;
    }

}