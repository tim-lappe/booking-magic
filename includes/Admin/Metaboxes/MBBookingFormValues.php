<?php


namespace TL_Booking\Admin\Metaboxes;


use TL_Booking\Booking\BookingManager;
use WP_Post;

class MBBookingFormValues extends MetaBoxBase {

    function GetOnPostTypes(): array {
        return array(TLBM_PT_BOOKING);
    }

    function RegisterMetaBox() {
        $this->AddMetaBox("booking_form_values", "Form Values");
    }

    function PrintMetaBox(WP_Post $post) {
        $booking = BookingManager::GetBooking($post->ID);

        $form_values = $booking->booking_values;
        ?>
        <table class="tlbm-form-values-table">
            <tbody>
            <?php
            foreach($form_values as $key => $form_value) {

                ?>
                <tr>
                    <td class="tlbm-value-title"><?php echo $form_value->title ?></td>
                    <td><?php echo $form_value->value ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php
    }
}