<?php


namespace TLBM\Admin\Metaboxes;


use TLBM\Booking\BookingManager;
use WP_Post;

if (!defined('ABSPATH')) {
    return;
}

class MBBookingDebug extends MetaBoxBase {

    /**
     * @inheritDoc
     */
    function GetOnPostTypes(): array {
        return array(TLBM_PT_BOOKING);
    }

    /**
     * @inheritDoc
     */
    function RegisterMetaBox() {
        $this->AddMetaBox("booking_debug", "Debug");
    }

    /**
     * @inheritDoc
     */
    function PrintMetaBox(WP_Post $post) {
        $booking = BookingManager::GetBooking($post->ID);
        echo "<pre>";
        var_dump($booking);
        echo "</pre>";
    }
}