<?php


namespace TLBM\Admin\Metaboxes;


use TLBM\Booking\BookingManager;
use WP_Post;

if ( !defined('ABSPATH')) {
    return;
}

class MBBookingDebug extends MetaBoxBase
{

    /**
     * @inheritDoc
     */
    public function GetOnPostTypes(): array
    {
        return array(TLBM_PT_BOOKING);
    }

    /**
     * @inheritDoc
     */
    public function RegisterMetaBox()
    {
        $this->AddMetaBox("booking_debug", "Debug");
    }

    /**
     * @inheritDoc
     */
    public function PrintMetaBox(WP_Post $post)
    {
        $booking = BookingManager::GetBooking($post->ID);
    }
}