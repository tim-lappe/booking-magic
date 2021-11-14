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
				$bc->calendar_setup = self::GetCalendarSetup($post_id);

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
	public static function GetAllCalendars($options = array(), $orderby = "title", $order = "desc"): array {
        $posts = get_posts(array_merge(array(
            "post_type" => TLBM_PT_CALENDAR,
        ), $options));

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

	/**
	 * Returns the Calendar Setup from the given Post-Id
	 *
	 * @param int $id The Post-Id of the Calendar
	 *
	 * @return CalendarSetup|false
	 */
	public static function GetCalendarSetup($id) {
		$calendar_setup = get_post_meta($id, TLBM_CALENDAR_META_CALENDAR_SETUP, true);
		if( $calendar_setup instanceof CalendarSetup) {
			return $calendar_setup;
		}

		return new CalendarSetup();
	}

	/**
	 * Saves the Calendar Setup to the Calendar Post
	 *
	 * @param $id
	 * @param CalendarSetup $calendar_setup
	 *
	 * @return bool
	 */
	public static function SaveCalendarSetup($id, $calendar_setup): bool {
		if($calendar_setup instanceof CalendarSetup) {
			update_post_meta($id, TLBM_CALENDAR_META_CALENDAR_SETUP, $calendar_setup);
			return true;
		}
		return false;
	}
}