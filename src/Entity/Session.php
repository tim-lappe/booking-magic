<?php

namespace TLBM\Entity;


/**
 * Class Session
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="sessions")
 */
class Session
{
    /**
     * @var ?int
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false)
     */
    protected ?int $sessionId = null;
}