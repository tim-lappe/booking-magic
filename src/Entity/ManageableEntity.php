<?php

namespace TLBM\Entity;

use TLBM\ApiUtils\Contracts\TimeUtilsInterface;
use TLBM\Entity\Traits\IndexedEntity;
use TLBM\MainFactory;
use TLBM\Utilities\ExtendedDateTime;

abstract class ManageableEntity
{
    use IndexedEntity;


    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column(type="bigint", nullable=false)
     */
    protected int $timestampEdited;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column(type="bigint", nullable=false)
     */
    protected int $timestampCreated;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column(type="string", nullable=false)
     */
    protected string $administrationStatus = TLBM_ENTITY_ADMINSTATUS_ACTIVE;

    public function __construct()
    {
        $timeUtils = MainFactory::get(TimeUtilsInterface::class);

        $this->timestampCreated = $timeUtils->time();
        $this->timestampEdited = $timeUtils->time();
    }

    /**
     * @return int
     */
    public function getTimestampCreated(): int
    {
        return $this->timestampCreated;
    }

    /**
     * @return ExtendedDateTime
     */
    public function getDateTimeCreated(): ExtendedDateTime
    {
        return new ExtendedDateTime($this->timestampCreated);
    }

    /**
     * @return int
     */
    public function getTimestampEdited(): int
    {
        return $this->timestampEdited;
    }

    /**
     * @return ExtendedDateTime
     */
    public function getDateTimeEdited(): ExtendedDateTime
    {
        return new ExtendedDateTime($this->timestampEdited);
    }

    /**
     * @param int $timestampEdited
     */
    public function setTimestampEdited(int $timestampEdited): void
    {
        $this->timestampEdited = $timestampEdited;
    }

    /**
     * @return string
     */
    public function getAdministrationStatus(): string
    {
        return $this->administrationStatus;
    }

    /**
     * @param string $administrationStatus
     */
    public function setAdministrationStatus(string $administrationStatus): void
    {
        $this->administrationStatus = $administrationStatus;
    }
}