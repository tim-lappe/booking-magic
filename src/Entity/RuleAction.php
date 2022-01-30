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
    protected string $action_type;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string")
     */
    protected string $weekdays = "";

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer")
     */
    protected int $time_hour = 0;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="integer")
     */
    protected int $time_min = 0;

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
        return $this->action_type;
    }

    /**
     * @param string $action_type
     */
    public function setActionType(string $action_type): void
    {
        $this->action_type = $action_type;
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
        return $this->time_hour;
    }

    /**
     * @param int $time_hour
     */
    public function setTimeHour(int $time_hour): void
    {
        $this->time_hour = $time_hour;
    }

    /**
     * @return int
     */
    public function getTimeMin(): int
    {
        return $this->time_min;
    }

    /**
     * @param int $time_min
     */
    public function setTimeMin(int $time_min): void
    {
        $this->time_min = $time_min;
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
            "action_type" => $this->action_type,
            "weekdays"    => $this->weekdays,
            "time_hour"   => $this->time_hour,
            "time_min"    => $this->time_min,
            "priority"    => $this->priority,
            "actions"     => $this->data
        );
    }
}