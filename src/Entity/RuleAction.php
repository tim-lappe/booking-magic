<?php


namespace TLBM\Entity;

use JsonSerializable;

/**
 * Class RuleAction
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="rule_actions")
 */
class RuleAction implements JsonSerializable
{

    use IndexedTable;

    /**
     * @var ?Rule
     * @Doctrine\ORM\Mapping\ManyToOne (targetEntity=Rule::class)
     */
    protected ?Rule $rule;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string", nullable=false)
     */
    protected string $actionType;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string")
     */
    protected string $weekdays = "";

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer")
     */
    protected int $timeHour = 0;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer")
     */
    protected int $timeMin = 0;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer")
     */
    protected int $priority = 0;

    /**
     * @Doctrine\ORM\Mapping\Column (type="json", nullable=false)
     */
    protected ?array $data = null;

    public function __construct()
    {
        $this->data = array();
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data)
    {
        $this->data = $data;
    }

    /**
     * @return Rule
     */
    public function getRule(): Rule
    {
        return $this->rule;
    }

    /**
     * @param ?Rule $rule
     */
    public function setRule(?Rule $rule): void
    {
        $this->rule = $rule;
    }

    /**
     * @return string
     */
    public function getActionType(): string
    {
        return $this->actionType;
    }

    /**
     * @param string $actionType
     */
    public function setActionType(string $actionType): void
    {
        $this->actionType = $actionType;
    }

    /**
     * @return string
     */
    public function getWeekdays(): string
    {
        return $this->weekdays;
    }

    /**
     * @param string $weekdays
     */
    public function setWeekdays(string $weekdays): void
    {
        $this->weekdays = $weekdays;
    }

    /**
     * @return int
     */
    public function getTimeHour(): int
    {
        return $this->timeHour;
    }

    /**
     * @param int $timeHour
     */
    public function setTimeHour(int $timeHour): void
    {
        $this->timeHour = $timeHour;
    }

    /**
     * @return int
     */
    public function getTimeMin(): int
    {
        return $this->timeMin;
    }

    /**
     * @param int $timeMin
     */
    public function setTimeMin(int $timeMin): void
    {
        $this->timeMin = $timeMin;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function jsonSerialize(): array
    {
        return array(
            "id"          => $this->getId(),
            "action_type" => $this->actionType,
            "weekdays"    => $this->weekdays,
            "time_hour"   => $this->timeHour,
            "time_min"    => $this->timeMin,
            "priority"    => $this->priority,
            "actions"     => $this->data
        );
    }
}