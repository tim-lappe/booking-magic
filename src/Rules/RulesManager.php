<?php


namespace TLBM\Rules;


use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use TLBM\Calendar\Contracts\CalendarSelectionHandlerInterface;
use TLBM\Database\Contracts\ORMInterface;
use TLBM\Entity\Rule;
use TLBM\Rules\Contracts\RulesManagerInterface;
use TLBM\Utilities\Contracts\PeriodsToolsInterface;

if ( !defined('ABSPATH')) {
    return;
}

class RulesManager implements RulesManagerInterface
{

    /**
     * @var ORMInterface
     */
    private ORMInterface $repository;

    /**
     * @var CalendarSelectionHandlerInterface
     */
    private CalendarSelectionHandlerInterface $selectionHandler;

    /**
     * @var PeriodsToolsInterface
     */
    private PeriodsToolsInterface $periodsTools;

    public function __construct(
        ORMInterface $repository,
        CalendarSelectionHandlerInterface $selectionHandler,
        PeriodsToolsInterface $periodsTools
    ) {
        $this->repository       = $repository;
        $this->selectionHandler = $selectionHandler;
        $this->periodsTools     = $periodsTools;
    }

    /**
     * Get a Rule
     *
     * @param int $rule_id
     *
     * @return null|Rule
     */
    public function getRule($rule_id): ?Rule
    {
        try {
            $mgr  = $this->repository->getEntityManager();
            $rule = $mgr->find("\TLBM\Entity\Rule", $rule_id);
            if ($rule instanceof Rule) {
                return $rule;
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        return null;
    }

    /**
     * @param Rule $rule
     *
     * @throws Exception
     */
    public function saveRule(Rule $rule)
    {
        $mgr = $this->repository->getEntityManager();
        $mgr->persist($rule);
        $mgr->flush();
    }

    /**
     * @param array $options
     *
     * @return int
     */
    public function getAllRulesCount(array $options = array()): int
    {
        $mgr = $this->repository->getEntityManager();
        $queryBuilder  = $mgr->createQueryBuilder();
        $queryBuilder->select($queryBuilder->expr()->count("r"))->from("\TLBM\Entity\Rule", "r");

        $query = $queryBuilder->getQuery();
        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            return 0;
        }
    }

    /**
     * @param int $calendar_id
     * @param DateTime $dateTime
     * @param array $options
     * @param string $orderby
     * @param string $order
     *
     * @return Rule[]
     */
    public function getAllRulesForCalendarForDateTime(
        int $calendar_id,
        DateTime $dateTime,
        array $options = array(),
        string $orderby = "priority",
        string $order = "asc"
    ): array {
        $rules   = $this->getAllRulesForCalendar($calendar_id, $options, $orderby, $order);
        $dtRules = array();
        foreach ($rules as $rule) {
            if ($this->periodsTools->isDateTimeInPeriodCollection($rule->getPeriods()->toArray(), $dateTime)) {
                $dtRules[] = $rule;
            }
        }

        return $dtRules;
    }

    /**
     * Get all Rules that are affecting to the specific calendar_id
     *
     * @param int $calendar_id
     * @param array $options
     * @param string $orderby
     * @param string $order
     *
     * @return Rule[]
     */
    public function getAllRulesForCalendar(
        int $calendar_id,
        array $options = array(),
        string $orderby = "priority",
        string $order = "asc"
    ): array {
        $rules          = $this->getAllRules($options, $orderby, $order);
        $calendar_rules = array();
        foreach ($rules as $rule) {
            if ($this->selectionHandler->containsCalendar($rule->getCalendarSelection(), $calendar_id)) {
                $calendar_rules[] = $rule;
            }
        }

        return $calendar_rules;
    }

    /**
     * Get all Rules
     *
     * @param array $options
     * @param string $orderby
     * @param string $order
     * @param int $offset
     * @param int $limit
     *
     * @return Rule[]
     */
    public function getAllRules(
        array $options = array(),
        string $orderby = "title",
        string $order = "desc",
        int $offset = 0,
        int $limit = 0
    ): array {
        $mgr = $this->repository->getEntityManager();
        $qb  = $mgr->createQueryBuilder();
        $qb->select("r")->from("\TLBM\Entity\Rule", "r")->orderBy("r." . $orderby, $order)->setFirstResult($offset);
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }

        $query  = $qb->getQuery();
        $result = $query->getResult();

        if (is_array($result)) {
            return $result;
        }

        return array();
    }

    /**
     * @param Rule $rule
     * @param DateTime $date_time
     */
    public function doesRuleWorksOnDateTime(Rule $rule, DateTime $date_time)
    {
    }
}