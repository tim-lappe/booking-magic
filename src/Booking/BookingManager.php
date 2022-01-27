<?php


namespace TLBM\Booking;


use TLBM\Admin\Settings\SingleSettings\BookingProcess\DefaultBookingState;
use TLBM\Model\Booking;

if ( !defined('ABSPATH')) {
    return;
}

class BookingManager
{

    /**
     * @param Booking $booking
     */
    public static function SetBooking(Booking $booking)
    {
        if ( !($booking->wp_post_id > 0)) {
            $post_id = wp_insert_post(array(
                                          'post_title'  => empty($booking->title) ? time() : $booking->title,
                                          'post_status' => 'publish',
                                          'post_type'   => TLBM_PT_BOOKING
                                      ));

            $booking->wp_post_id = $post_id;
        }

        self::UpdateBooking($booking);
    }

    /**
     * @param Booking $booking
     */
    private static function UpdateBooking(Booking $booking)
    {
        update_post_meta($booking->wp_post_id, "tlbm_booking_object", $booking);
    }

    /**
     * @param array $get_posts_options
     * @param string $orderby
     * @param string $order
     *
     * @return Booking[]
     */
    public static function GetAllBookings($get_posts_options = array(), $orderby = "priority", $order = "desc"): array
    {
        $wp_posts = get_posts(
            wp_parse_args($get_posts_options, array(
                "post_type"   => TLBM_PT_BOOKING,
                "numberposts" => -1
            ))
        );

        $bookings = array();
        foreach ($wp_posts as $post) {
            $bookings[] = self::GetBooking($post->ID);
        }

        usort($bookings, function ($a, $b) use ($orderby, $order) {
            if (strtolower($order) == "asc") {
                return $a->{$orderby} > $b->{$orderby};
            }

            return $a->{$orderby} < $b->{$orderby};
        });

        return $bookings;
    }

    /**
     * @param int $wp_post_id
     *
     * @return false|Booking
     */
    public static function GetBooking(int $wp_post_id)
    {
        $wp_post = get_post($wp_post_id);
        if ($wp_post != null) {
            $booking = get_post_meta($wp_post_id, "tlbm_booking_object", true);
            if ( !$booking instanceof Booking) {
                $booking             = new Booking();
                $booking->wp_post_id = $wp_post_id;
                $booking->state      = DefaultBookingState::getDefaultName();
            }

            return $booking;
        }

        return false;
    }

    /**
     * @param array $get_posts_options
     *
     * @return int
     */
    public static function GetAllBookingsCount($get_posts_options = array()): int
    {
        $wp_posts = get_posts(
            array(
                "post_type"   => TLBM_PT_BOOKING,
                "numberposts" => -1
            ) + $get_posts_options
        );

        return sizeof($wp_posts);
    }
}