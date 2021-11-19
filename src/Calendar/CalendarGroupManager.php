<?php


namespace TLBM\Calendar;


use TLBM\Model\Calendar;
use TLBM\Model\CalendarGroup;
use TLBM\Model\CalendarSelection;
use WP_Post;

class CalendarGroupManager {


	/**
	 * @param CalendarGroup $group
	 *
	 * @return Calendar[]
	 */
	public static function GetCalendarListFromGroup($group): array {
		return CalendarSelectionHandler::GetSelectedCalendarList($group->calendar_selection);
	}

	/**
	 * Returns the Calendar Group from the given Post-Id
	 *
	 * @param mixed $post_id The Post-Id of the Calendar
	 *
	 * @return ?CalendarGroup
	 */
	public static function GetCalendarGroup( $post_id ): ?CalendarGroup {
		$group_post = get_post($post_id);
		if($group_post instanceof WP_Post) {
			if($group_post->post_type == TLBM_PT_CALENDAR_GROUPS) {
				$group = new CalendarGroup();
				$group->wp_post_id = $post_id;
				$group->booking_distribution = get_post_meta($post_id, "booking_distribution", true);
				if(!$group->booking_distribution) {
					$group->booking_distribution = TLBM_BOOKING_DISTRIBUTION_EVENLY;
				}
				$group->calendar_selection = get_post_meta($post_id, "calendar_selection", true);
				if(!$group->calendar_selection) {
					$group->calendar_selection = new CalendarSelection();
				}
				$group->title = $group_post->post_title;
				return $group;
			}
		}

		return null;
	}


	/**
	 * Return a List of all active Groups
	 *
	 * @param array $get_posts_options
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return Calendar[]
	 */
	public static function GetAllGroups($get_posts_options = array(), $orderby = "title", $order = "desc"): array {
		$posts = get_posts(array_merge(array(
			"post_type" => TLBM_PT_CALENDAR_GROUPS,
			"numberposts" => -1
		), $get_posts_options));

		$groups = array();
		foreach ($posts as $post) {
			$groups[] = self::GetCalendarGroup($post->ID);
		}

		usort($groups, function ($a, $b) use ($orderby, $order) {
			if(strtolower($order) == "asc") {
				return $a->{$orderby} > $b->{$orderby};
			}
			if(strtolower($order) == "desc") {
				return $a->{$orderby} < $b->{$orderby};
			}
			return $a->{$orderby} < $b->{$orderby};
		});

		return $groups;
	}

	public static function GetAllGroupsCount($get_posts_options = array()): int {
		$posts = get_posts(array_merge(array(
			"post_type" => TLBM_PT_CALENDAR_GROUPS,
			"numberposts" => -1
		), $get_posts_options));

		return sizeof($posts);
	}
}