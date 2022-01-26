<?php


namespace TLBM\Admin\Metaboxes;


use TLBM\Admin\WpForm\BookingStateSelectField;
use TLBM\Admin\WpForm\FormBuilder;
use TLBM\Booking\BookingManager;
use WP_Post;

class MBBookingActions extends MetaBoxForm
{

    public function GetOnPostTypes(): array
    {
        return array(TLBM_PT_BOOKING);
    }

    public function RegisterMetaBox()
    {
        $this->AddMetaBox("booking_actions", "Manage Booking");
    }

    public function PrintMetaBox(WP_Post $post)
    {
        $form_builder = new FormBuilder();

        $booking = BookingManager::GetBooking($post->ID);

        $form_builder->displayFormHead();
        $form_builder->displayFormField(new BookingStateSelectField("booking_state", "State", $booking->state));
        $form_builder->displayFormFooter();
    }

    public function OnSave($post_id)
    {
        if (isset($_REQUEST['booking_state'])) {
            $statename      = $_REQUEST['booking_state'];
            $booking        = BookingManager::GetBooking($post_id);
            $booking->state = $statename;
            BookingManager::SetBooking($booking);
        }
    }
}