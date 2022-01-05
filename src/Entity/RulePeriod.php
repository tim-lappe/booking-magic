<?php


namespace TLBM\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as OrmMapping;
use JsonSerializable;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @OrmMapping\Entity
 * @OrmMapping\Table(name="rule_periods")
 */
class RulePeriod implements JsonSerializable {

	use IndexedTable;

	/**
	 * @var ?Rule
	 * @OrmMapping\ManyToOne (targetEntity=Rule::class)
	 */
	protected ?Rule $rule;

	/**
	 * @var ArrayCollection
	 * @OrmMapping\OneToMany (targetEntity=TimeSlot::class, mappedBy="rule_period", orphanRemoval=true, cascade={"all"})
	 */
	protected Collection $daily_time_ranges;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer", nullable=false)
	 */
	protected int $from_tstamp;

    /**
     * @var bool
     * @OrmMapping\Column (type="boolean", nullable=false)
     */
    protected bool $from_timeset = false;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer")
	 */
	protected int $to_tstamp;


    /**
     * @return bool
     */
    public function IsFromTimeset(): bool {
        return $this->from_timeset;
    }

    /**
     * @param bool $from_timeset
     */
    public function SetFromTimeset(bool $from_timeset): void {
        $this->from_timeset = $from_timeset;
    }

    /**
     * @return bool
     */
    public function IsToTimeset(): bool {
        return $this->to_timeset;
    }

    /**
     * @param bool $to_timeset
     */
    public function SetToTimeset(bool $to_timeset): void {
        $this->to_timeset = $to_timeset;
    }

    /**
     * @var bool
     * @OrmMapping\Column (type="boolean", nullable=false)
     */
    protected bool $to_timeset = false;

    /**
     * @param TimeSlot $slot
     * @return TimeSlot
     */
	public function AddTimeSlot(TimeSlot $slot): TimeSlot {
		if(!$this->daily_time_ranges->contains($slot)) {
			$this->daily_time_ranges[] = $slot;
            $slot->SetRulePeriod($this);
		}

		return $slot;
	}

    /**
     * @param TimeSlot $slot
     * @return TimeSlot
     */
	public function RemoveTimeSlot(TimeSlot $slot): TimeSlot {
		if($this->daily_time_ranges->contains($slot)) {
			$this->daily_time_ranges->removeElement($slot);
            $slot->SetRulePeriod(null);
		}
		return $slot;
	}

    /**
     * @param int $from_tstamp
     * @return void
     */
    public function SetFromTstamp(int $from_tstamp) {
        $this->from_tstamp = $from_tstamp;
    }

    /**
     * @return int
     */
    public function GetFromTstamp(): int {
        return $this->from_tstamp;
    }

    /**
     * @param int $to_tstamp
     * @return void
     */
    public function SetToTstamp(int $to_tstamp) {
        $this->to_tstamp = $to_tstamp;
    }

    /**
     * @return int
     */
    public function GetToTstamp(): int {
        return $this->to_tstamp;
    }

    public function SetRule($rule) {
        $this->rule = $rule;
    }

    public function GetRule(): Rule {
        return $this->rule;
    }

    /**
     *
     */
	public function __construct() {
		$this->daily_time_ranges = new ArrayCollection();
	}

    public function jsonSerialize(): array {
        return array (
            "from_tstamp" => $this->from_tstamp,
            "from_timeset" => $this->from_timeset,
            "to_tstamp" => $this->to_tstamp,
            "to_timeset" => $this->to_timeset,
            "id" => $this->id,
            "daily_time_ranges" => $this->daily_time_ranges->toArray()
        );
    }
}