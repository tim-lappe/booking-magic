<?php


namespace TLBM\Model;


class Booking {

    /**
     * @var int $wp_post_id The WP Post Id
     */
    public $wp_post_id;

    /**
     * @var BookingValue[] Array of Form Values e.g. Name, Address etc.
     */
    public $booking_values = array();

	/**
	 * @var CalendarSlot[]
	 */
    public $calendar_slots = array();

	/**
	 * @var string
	 */
    public $title;

	/**
	 * @var int
	 */
    public $priority;

    public function __get( $name ) {

    }
}