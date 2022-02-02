<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use TLBM\Utilities\ExtendedDateTime;

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
     * @Doctrine\ORM\Mapping\OneToMany (targetEntity=TimeSlot::class, mappedBy="rulePeriod", orphanRemoval=true, cascade={"all"})
     */
    protected Collection $dailyTimeRanges;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="bigint", nullable=false)
     */
    protected int $fromTimestamp;

    /**
     * @var bool
     * @Doctrine\ORM\Mapping\Column (type="boolean", nullable=false)
     */
    protected bool $fromTimeset = false;

    /**
     * @var ?int
     * @Doctrine\ORM\Mapping\Column (type="bigint", nullable=true)
     */
    protected ?int $toTimestamp = null;

    /**
     * @var bool
     * @Doctrine\ORM\Mapping\Column (type="boolean", nullable=false)
     */
    protected bool $toTimeset = false;

    /**
     *
     */
    public function __construct()
    {
        $this->dailyTimeRanges = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isFromTimeset(): bool
    {
        return $this->fromTimeset;
    }

    /**
     * @param bool $fromTimeset
     */
    public function setFromTimeset(bool $fromTimeset): void
    {
        $this->fromTimeset = $fromTimeset;
    }

    /**
     * @return bool
     */
    public function isToTimeset(): bool
    {
        return $this->toTimeset;
    }

    /**
     * @param bool $toTimeset
     */
    public function setToTimeset(bool $toTimeset): void
    {
        $this->toTimeset = $toTimeset;
    }

    /**
     * @param TimeSlot $slot
     *
     * @return TimeSlot
     */
    public function addTimeSlot(TimeSlot $slot): TimeSlot
    {
        if ( !$this->dailyTimeRanges->contains($slot)) {
            $this->dailyTimeRanges[] = $slot;
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
        if ($this->dailyTimeRanges->contains($slot)) {
            $this->dailyTimeRanges->removeElement($slot);
            $slot->setRulePeriod(null);
        }

        return $slot;
    }

    /**
     * @return int
     */
    public function getFromTimestamp(): int
    {
        return $this->fromTimestamp;
    }

    /**
     * @param int $fromTimestamp
     *
     * @return void
     */
    public function setFromTimestamp(int $fromTimestamp)
    {
        $this->fromTimestamp = $fromTimestamp;
    }

    /**
     * @return ?int
     */
    public function getToTimestamp(): ?int
    {
        return $this->toTimestamp;
    }

    /**
     * @param ?int $toTimestamp
     *
     * @return void
     */
    public function setToTimestamp(?int $toTimestamp)
    {
        $this->toTimestamp = $toTimestamp;
    }

    /**
     * @return Collection
     */
    public function getDailyTimeRanges()
    {
        return $this->dailyTimeRanges;
    }

    /**
     * @param Collection $dailyTimeRanges
     */
    public function setDailyTimeRanges(Collection $dailyTimeRanges): void
    {
        $this->dailyTimeRanges = $dailyTimeRanges;
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
        return [
            "fromDateTime"    => new ExtendedDateTime($this->fromTimestamp),
            "fromTimeset"      => $this->fromTimeset,
            "toDateTime"      => $this->toTimestamp ? new ExtendedDateTime($this->toTimestamp): null,
            "toTimeset"       => $this->toTimeset,
            "id"              => $this->id,
            "dailyTimeRanges" => $this->dailyTimeRanges->toArray()
        ];
    }
}