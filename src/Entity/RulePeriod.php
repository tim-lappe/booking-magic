<?php


namespace TLBM\Entity;

use JsonSerializable;
use TLBM\Entity\Traits\IndexedEntity;
use TLBM\Utilities\ExtendedDateTime;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="rule_periods")
 */
class RulePeriod implements JsonSerializable
{

    use IndexedEntity;

    /**
     * @var ?Rule
     * @Doctrine\ORM\Mapping\ManyToOne (targetEntity=Rule::class)
     */
    protected ?Rule $rule;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="bigint", nullable=false)
     */
    protected int $fromTimestamp;

    /**
     * @var bool
     * @Doctrine\ORM\Mapping\Column (type="smallint", nullable=false)
     */
    protected bool $fromFullDay = false;

    /**
     * @var ?int
     * @Doctrine\ORM\Mapping\Column (type="bigint", nullable=true)
     */
    protected ?int $toTimestamp = null;

    /**
     * @var bool
     * @Doctrine\ORM\Mapping\Column (type="smallint", nullable=false)
     */
    protected bool $toFullDay = false;

    /**
     * @param Rule|null $rule
     * @param int $fromTimestamp
     * @param bool $fromFullDay
     * @param int|null $toTimestamp
     * @param bool $toFullDay
     */
    public function __construct(?Rule $rule = null, int $fromTimestamp = 0, bool $fromFullDay = false, ?int $toTimestamp = null, bool $toFullDay = false)
    {
        $this->rule = $rule;
        $this->fromTimestamp = $fromTimestamp;
        $this->fromFullDay = $fromFullDay;
        $this->toTimestamp = $toTimestamp;
        $this->toFullDay = $toFullDay;
    }

    /**
     * @return bool
     */
    public function isFromFullDay(): bool
    {
        return $this->fromFullDay;
    }

    /**
     * @param bool $fromFullDay
     */
    public function setFromFullDay(bool $fromFullDay): void
    {
        $this->fromFullDay = $fromFullDay;
    }

    /**
     * @return bool
     */
    public function isToFullDay(): bool
    {
        return $this->toFullDay;
    }

    /**
     * @param bool $toFullDay
     */
    public function setToFullDay(bool $toFullDay): void
    {
        $this->toFullDay = $toFullDay;
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
        $fromDateTime = new ExtendedDateTime($this->fromTimestamp);
        $fromDateTime->setFullDay($this->fromFullDay);

        if($this->toTimestamp != null) {
            $toDateTime = new ExtendedDateTime($this->toTimestamp);
            $toDateTime->setFullDay($this->toFullDay);
        } else {
            $toDateTime = null;
        }

        return [
            "fromDateTime"    => $fromDateTime,
            "toDateTime"      => $toDateTime,
            "id"              => $this->id,
        ];
    }
}