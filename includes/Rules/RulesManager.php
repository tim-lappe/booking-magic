<?php


namespace TL_Booking\Rules;


use TL_Booking\Calendar\CalendarSelectionHandler;
use TL_Booking\Model\CalendarSelection;
use TL_Booking\Model\PeriodCollection;
use TL_Booking\Model\Rule;
use TL_Booking\Model\RuleActionCollection;
use TL_Booking\Utilities\PeriodsTools;
use WP_Post;

if (!defined('ABSPATH')) {
    return;
}

class RulesManager {

    /**
     * Get a Rule
     *
     * @param $post_id
     *
     * @return false|Rule
     */
    public static function GetRule($post_id) {
        $rule = get_post($post_id);
        if($rule instanceof WP_Post) {
            if($rule->post_type == TLBM_PT_RULES) {
                $r = new Rule();
                $r->wp_post_id = $post_id;
                $r->title = $rule->post_title;

                $calendar_selection = get_post_meta($post_id, "calendar_selection", true);
                if($calendar_selection instanceof CalendarSelection) {
                    $r->calendar_selection = $calendar_selection;
                } else {
                    $r->calendar_selection = new CalendarSelection();
                }

                $rule_actions = get_post_meta($post_id, "actions", true);
                if($rule_actions && $rule_actions instanceof RuleActionCollection) {
                	$r->action = $rule_actions;
                } else {
                	$r->action = new RuleActionCollection();
                }

                $periods = get_post_meta($post_id, "periods", true);
                if($periods instanceof PeriodCollection) {
                	$r->periods = $periods;
                } else {
                	$r->periods = new PeriodCollection();
                }

                $priority = get_post_meta($post_id, "priority", true);
                if(!$priority) {
                    $priority = 10;
                }

                $r->priority = $priority;

                return $r;
            }
        }
        return false;
    }

    /**
     * Get all Rules
     *
     * @param array  $get_posts_options
     * @param string $orderby
     * @param string $order
     *
     * @return Rule[]
     */
    public static function GetAllRules($get_posts_options = array(), $orderby = "priority", $order = "desc"): array {
        $posts = get_posts(array(
            "post_type" => TLBM_PT_RULES,
	        "numberposts" => -1
        ) + $get_posts_options);

        $rules = array();
        foreach ($posts as $post) {
            $rules[] = self::GetRule($post->ID);
        }

        usort($rules, function ($a, $b) use ($orderby, $order) {
            if(strtolower($order) == "asc") {
                return $a->{$orderby} > $b->{$orderby};
            }
            if(strtolower($order) == "desc") {
                return $a->{$orderby} < $b->{$orderby};
            }
        });


        return $rules;
    }

    /**
     * Get all Rules that are affecting to the specific calendar_id
     *
     * @param        $calendar_id
     * @param array  $get_posts_options
     * @param string $orderby
     * @param string $order
     *
     * @return Rule[]
     */
    public static function GetAllRulesForCalendar($calendar_id, $get_posts_options = array(), $orderby = "priority", $order = "asc"): array {
        $rules = self::GetAllRules($get_posts_options, $orderby, $order);

        $calendar_rules = array();
        foreach($rules as $rule) {
            if(CalendarSelectionHandler::ContainsCalendar($rule->calendar_selection, $calendar_id)) {
                $calendar_rules[] = $rule;
            }
        }

        return $calendar_rules;
    }

	/**
	 * @param $calendar_id
	 * @param \DateTime $dateTime
	 * @param array $get_posts_options
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return Rule[]
	 */
    public static function GetAllRulesForCalendarForDateTime( $calendar_id, \DateTime $dateTime, $get_posts_options = array(), $orderby = "priority", $order = "asc" ): array {
		$rules = self::GetAllRulesForCalendar($calendar_id, $get_posts_options, $orderby, $order);
		$dtRules = array();
		foreach ($rules as $rule) {
			if(PeriodsTools::IsDateTimeInPeriodCollection($rule->periods, $dateTime)) {
				$dtRules[] = $rule;
			}
		}

		return $dtRules;
    }

	/**
	 * @param Rule $rule
	 * @param \DateTime $date_time
	 */
    public static function DoesRuleWorksOnDateTime( Rule $rule, \DateTime $date_time ) {

    }
}