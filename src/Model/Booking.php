<?php


namespace TLBM\Model;


use TLBM\Admin\Settings\SingleSettings\BookingProcess\DefaultBookingState;

class Booking
{

    /**
     * @var int $wp_post_id The WP Post Id
     */
    public int $wp_post_id = 0;

    /**
     * @var BookingValue[] Array of Form Values e.g. Name, Address etc.
     */
    public array $booking_values = array();

    /**
     * @var CalendarSlot[]
     */
    public array $calendar_slots = array();

    /**
     * @var string
     */
    public string $title = "";

    /**
     * @var int
     */
    public int $priority = 10;

    /**
     * @var string
     */
    public string $state = "";


    public function __construct()
    {
        $this->state = DefaultBookingState::getDefaultName();
    }
}