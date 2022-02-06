<?php


namespace TLBM\Entity;

use JsonSerializable;
use TLBM\Entity\Traits\IndexedEntity;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="rule_period_time_slots")
 */
class TimeSlot implements JsonSerializable
{

    use IndexedEntity;

    /**
     * @var ?RulePeriod
     * @Doctrine\ORM\Mapping\ManyToOne (targetEntity=RulePeriod::class)
     */
    protected ?RulePeriod $rulePeriod;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer", nullable=false)
     */
    protected int $fromHour;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer", nullable=false)
     */
    protected int $fromMin;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer", nullable=false)
     */
    protected int $toHour;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer", nullable=false)
     */
    protected int $toMin;

    /**
     * @return ?RulePeriod
     */
    public function getRulePeriod(): ?RulePeriod
    {
        return $this->rulePeriod;
    }

    /**
     * @param ?RulePeriod $rulePeriod
     */
    public function setRulePeriod(?RulePeriod $rulePeriod): void
    {
        $this->rulePeriod = $rulePeriod;
    }

    /**
     * @return int
     */
    public function getFromHour(): int
    {
        return $this->fromHour;
    }

    /**
     * @param int $fromHour
     */
    public function setFromHour(int $fromHour): void
    {
        $this->fromHour = $fromHour;
    }

    /**
     * @return int
     */
    public function getFromMin(): int
    {
        return $this->fromMin;
    }

    /**
     * @param int $fromMin
     */
    public function setFromMin(int $fromMin): void
    {
        $this->fromMin = $fromMin;
    }

    /**
     * @return int
     */
    public function getToHour(): int
    {
        return $this->toHour;
    }

    /**
     * @param int $toHour
     */
    public function setToHour(int $toHour): void
    {
        $this->toHour = $toHour;
    }

    /**
     * @return int
     */
    public function getToMin(): int
    {
        return $this->toMin;
    }

    /**
     * @param int $toMin
     */
    public function setToMin(int $toMin): void
    {
        $this->toMin = $toMin;
    }


    public function jsonSerialize(): array
    {
        return array(
            "id"        => $this->id,
            "from_min"  => $this->fromMin,
            "from_hour" => $this->fromHour,
            "to_min"    => $this->toMin,
            "to_hour"   => $this->toHour
        );
    }
}