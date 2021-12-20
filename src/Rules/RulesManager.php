<?php


namespace TLBM\Rules;


use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use TLBM\Calendar\CalendarSelectionHandler;
use TLBM\Database\OrmManager;
use TLBM\Entity\Rule;
use TLBM\Utilities\PeriodsTools;

if (!defined('ABSPATH')) {
    return;
}

class RulesManager {

    /**
     * Get a Rule
     *
     * @param $id
     * @return false|Rule
     */
    public static function GetRule($id): ?Rule {
        try {
            $mgr = OrmManager::GetEntityManager();
            $rule = $mgr->find("\TLBM\Entity\Rule", $id);
            if ($rule instanceof Rule) {
                return $rule;
            }
        } catch (Exception $e) {
            var_dump($e);
        }
        return null;
    }

    /**
     * @param Rule $rule
     * @throws Exception
     */
    public static function SaveRule( Rule $rule ) {
        $mgr = OrmManager::GetEntityManager();
        $mgr->persist($rule);
        $mgr->flush();
    }

    /**
     * Get all Rules
     *
     * @param array $options
     * @param string $orderby
     * @param string $order
     * @param int $offset
     * @param int $limit
     * @return Rule[]
     */
    public static function GetAllRules(array $options = array(), string $orderby = "title", string $order = "desc", int $offset = 0, int $limit = 0): array {
        $mgr = OrmManager::GetEntityManager();
        $qb = $mgr->createQueryBuilder();
        $qb ->select("r")
            ->from("\TLBM\Entity\Rule", "r")
            ->orderBy("r." . $orderby, $order)
            ->setFirstResult($offset);
        if($limit > 0) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $result = $query->getResult();

        if(is_array($result)) {
            return $result;
        }

        return array();
    }

    /**
     * @param array $options
     * @return int
     */
    public static function GetAllRulesCount(array $options = array()): int {
        $mgr = OrmManager::GetEntityManager();
        $qb = $mgr->createQueryBuilder();
        $qb ->select($qb->expr()->count("r"))
            ->from("\TLBM\Entity\Rule", "r");

        $query = $qb->getQuery();
        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }

    /**
     * Get all Rules that are affecting to the specific calendar_id
     *
     * @param int $calendar_id
     * @param array $options
     * @param string $orderby
     * @param string $order
     * @return Rule[]
     */
    public static function GetAllRulesForCalendar(int $calendar_id, array $options = array(), string $orderby = "priority", string $order = "asc"): array {
        $rules = self::GetAllRules($options, $orderby, $order);
        $calendar_rules = array();
        foreach($rules as $rule) {
            if(CalendarSelectionHandler::ContainsCalendar($rule->GetCalendarSelection(), $calendar_id)) {
                $calendar_rules[] = $rule;
            }
        }

        return $calendar_rules;
    }

	/**
	 * @param $calendar_id
	 * @param DateTime $dateTime
	 * @param array $options
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return Rule[]
	 */
    public static function GetAllRulesForCalendarForDateTime($calendar_id, DateTime $dateTime, array $options = array(), string $orderby = "priority", string $order = "asc" ): array {
		$rules = self::GetAllRulesForCalendar($calendar_id, $options, $orderby, $order);
		$dtRules = array();
		foreach ($rules as $rule) {
			if(PeriodsTools::IsDateTimeInPeriodCollection($rule->GetPeriods()->toArray(), $dateTime)) {
				$dtRules[] = $rule;
			}
		}

		return $dtRules;
    }

	/**
	 * @param Rule $rule
	 * @param DateTime $date_time
	 */
    public static function DoesRuleWorksOnDateTime( Rule $rule, DateTime $date_time ) {

    }
}