<?php

namespace TLBM\Calendar;

use TLBM\Model\Calendar;
use TLBM\Model\CalendarSetup;
use WP_Post;

if( ! defined( 'ABSPATH' ) ) {
	return;
}

class CalendarManager {

	/**
	 * Returns the BookingCalender from the given Post-Id
	 *
	 * @param mixed $post_id The Post-Id of the Calendar
	 *
	 * @return Calendar|false
	 */
	public static function GetCalendar( $post_id ) {
		$calendar_post = get_post($post_id);
		if($calendar_post instanceof WP_Post) {
			if($calendar_post->post_type == TLBM_PT_CALENDAR) {
				$bc = new Calendar();
				$bc->wp_post_id = $post_id;
				$bc->title = $calendar_post->post_title;

				return $bc;
			}
		}
		return false;
	}

	/**
	 * Return a List of all active Calendars
	 *
	 * @param array $options
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return Calendar[]
	 */
	public static function GetAllCalendars($get_posts_options = array(), $orderby = "title", $order = "desc"): array {
        $posts = get_posts(array_merge(array(
            "post_type" => TLBM_PT_CALENDAR,
	        "numberposts" => -1
        ), $get_posts_options));

        $calendars = array();
        foreach ($posts as $post) {
            $calendars[] = self::GetCalendar($post->ID);
        }

		usort($calendars, function ($a, $b) use ($orderby, $order) {
			if(strtolower($order) == "asc") {
				return $a->{$orderby} > $b->{$orderby};
			}
			if(strtolower($order) == "desc") {
				return $a->{$orderby} < $b->{$orderby};
			}
			return $a->{$orderby} < $b->{$orderby};
		});

        return $calendars;
	}

	public static function GetAllCalendarsCount($get_posts_options = array()): int {
		$posts = get_posts(array_merge(array(
			"post_type" => TLBM_PT_CALENDAR,
			"numberposts" => -1
		), $get_posts_options));

		return sizeof($posts);
	}
}