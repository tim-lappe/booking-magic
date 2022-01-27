<?php


namespace TLBM\Entity;

use JsonSerializable;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="rule_period_time_slots")
 */
class TimeSlot implements JsonSerializable
{

    use IndexedTable;

    /**
     * @var ?RulePeriod
     * @Doctrine\ORM\Mapping\ManyToOne (targetEntity=RulePeriod::class)
     */
    protected ?RulePeriod $rule_period;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer", nullable=false)
     */
    protected int $from_hour;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer", nullable=false)
     */
    protected int $from_min;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer", nullable=false)
     */
    protected int $to_hour;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer", nullable=false)
     */
    protected int $to_min;

    /**
     * @return ?RulePeriod
     */
    public function getRulePeriod(): ?RulePeriod
    {
        return $this->rule_period;
    }

    /**
     * @param ?RulePeriod $rule_period
     */
    public function setRulePeriod(?RulePeriod $rule_period): void
    {
        $this->rule_period = $rule_period;
    }

    /**
     * @return int
     */
    public function getFromHour(): int
    {
        return $this->from_hour;
    }

    /**
     * @param int $from_hour
     */
    public function setFromHour(int $from_hour): void
    {
        $this->from_hour = $from_hour;
    }

    /**
     * @return int
     */
    public function getFromMin(): int
    {
        return $this->from_min;
    }

    /**
     * @param int $from_min
     */
    public function setFromMin(int $from_min): void
    {
        $this->from_min = $from_min;
    }

    /**
     * @return int
     */
    public function getToHour(): int
    {
        return $this->to_hour;
    }

    /**
     * @param int $to_hour
     */
    public function setToHour(int $to_hour): void
    {
        $this->to_hour = $to_hour;
    }

    /**
     * @return int
     */
    public function getToMin(): int
    {
        return $this->to_min;
    }

    /**
     * @param int $to_min
     */
    public function setToMin(int $to_min): void
    {
        $this->to_min = $to_min;
    }


    public function jsonSerialize(): array
    {
        return array(
            "id"        => $this->id,
            "from_min"  => $this->from_min,
            "from_hour" => $this->from_hour,
            "to_min"    => $this->to_min,
            "to_hour"   => $this->to_hour
        );
    }
}