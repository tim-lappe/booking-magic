<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="rule_periods")
 */
class RulePeriod implements JsonSerializable
{

    use IndexedTable;

    /**
     * @var ?Rule
     * @Doctrine\ORM\Mapping\ManyToOne (targetEntity=Rule::class)
     */
    protected ?Rule $rule;

    /**
     * @var Collection
     * @Doctrine\ORM\Mapping\OneToMany (targetEntity=TimeSlot::class, mappedBy="rule_period", orphanRemoval=true, cascade={"all"})
     */
    protected Collection $daily_time_ranges;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer", nullable=false)
     */
    protected int $from_tstamp;

    /**
     * @var bool
     * @Doctrine\ORM\Mapping\Column (type="boolean", nullable=false)
     */
    protected bool $from_timeset = false;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer")
     */
    protected int $to_tstamp;
    /**
     * @var bool
     * @Doctrine\ORM\Mapping\Column (type="boolean", nullable=false)
     */
    protected bool $to_timeset = false;

    /**
     *
     */
    public function __construct()
    {
        $this->daily_time_ranges = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isFromTimeset(): bool
    {
        return $this->from_timeset;
    }

    /**
     * @param bool $from_timeset
     */
    public function setFromTimeset(bool $from_timeset): void
    {
        $this->from_timeset = $from_timeset;
    }

    /**
     * @return bool
     */
    public function isToTimeset(): bool
    {
        return $this->to_timeset;
    }

    /**
     * @param bool $to_timeset
     */
    public function setToTimeset(bool $to_timeset): void
    {
        $this->to_timeset = $to_timeset;
    }

    /**
     * @param TimeSlot $slot
     *
     * @return TimeSlot
     */
    public function addTimeSlot(TimeSlot $slot): TimeSlot
    {
        if ( !$this->daily_time_ranges->contains($slot)) {
            $this->daily_time_ranges[] = $slot;
            $slot->setRulePeriod($this);
        }

        return $slot;
    }

    /**
     * @param TimeSlot $slot
     *
     * @return TimeSlot
     */
    public function removeTimeSlot(TimeSlot $slot): TimeSlot
    {
        if ($this->daily_time_ranges->contains($slot)) {
            $this->daily_time_ranges->removeElement($slot);
            $slot->setRulePeriod(null);
        }

        return $slot;
    }

    /**
     * @return int
     */
    public function getFromTstamp(): int
    {
        return $this->from_tstamp;
    }

    /**
     * @param int $from_tstamp
     *
     * @return void
     */
    public function setFromTstamp(int $from_tstamp)
    {
        $this->from_tstamp = $from_tstamp;
    }

    /**
     * @return int
     */
    public function getToTstamp(): int
    {
        return $this->to_tstamp;
    }

    /**
     * @param int $to_tstamp
     *
     * @return void
     */
    public function setToTstamp(int $to_tstamp)
    {
        $this->to_tstamp = $to_tstamp;
    }

    /**
     * @return Collection
     */
    public function getDailyTimeRanges()
    {
        return $this->daily_time_ranges;
    }

    /**
     * @param Collection $daily_time_ranges
     */
    public function setDailyTimeRanges(Collection $daily_time_ranges): void
    {
        $this->daily_time_ranges = $daily_time_ranges;
    }

    public function getRule(): Rule
    {
        return $this->rule;
    }

    public function setRule($rule)
    {
        $this->rule = $rule;
    }

    public function jsonSerialize(): array
    {
        return array(
            "from_tstamp"       => $this->from_tstamp,
            "from_timeset"      => $this->from_timeset,
            "to_tstamp"         => $this->to_tstamp,
            "to_timeset"        => $this->to_timeset,
            "id"                => $this->id,
            "daily_time_ranges" => $this->daily_time_ranges->toArray()
        );
    }
}