<?php


namespace TLBM\Admin\Metaboxes;


use TLBM\Booking\BookingManager;
use TLBM\Booking\MainValues;
use TLBM\Utilities\DateTimeTools;
use WP_Post;

class MBBookingInformations extends MetaBoxBase {

    function GetOnPostTypes(): array {
        return array(TLBM_PT_BOOKING);
    }

    function RegisterMetaBox() {
        $this->AddMetaBox("booking_informations", "Form Values");
    }

    function PrintMetaBox(WP_Post $post) {
        $booking = BookingManager::GetBooking($post->ID);
        $mainvalues = new MainValues($booking);
        ?>
        <div class="tlbm-admin-booking-information">
            <div class="tlbm-admin-booking-information-list">
                <?php
                if($mainvalues->HasName()) {
                    echo "<div class='tlbm-admin-booking-information-item'>";
                    echo "<span class='tlbm-admin-booking-headline'>".__("Name", TLBM_TEXT_DOMAIN)."</span><br>";
                    echo "<p class='tlbm-admin-booking-values'>" . $mainvalues->GetFullName() . "</p>";
                    echo "</div>";
                }
                if($mainvalues->HasAddress()) {
                    echo "<div class='tlbm-admin-booking-information-item'>";
                    echo "<span class='tlbm-admin-booking-headline'>".__("Address", TLBM_TEXT_DOMAIN)."</span><br>";
                    echo "<p class='tlbm-admin-booking-values'>" . $mainvalues->GetAddress() . "</p>";
                    echo "</div>";
                }
                if($mainvalues->HasContactEmail()) {
                    echo "<div class='tlbm-admin-booking-information-item'>";
                    echo "<span class='tlbm-admin-booking-headline'>".__("E-Mail", TLBM_TEXT_DOMAIN)."</span><br>";
                    echo "<p class='tlbm-admin-booking-values'>" . $mainvalues->GetContactEmail() . "</p>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
        <?php if($mainvalues->HasCalendar()): ?>
            <div class="tlbm-admin-booking-information">
                <div class="tlbm-admin-booking-information-list">
                    <div class="tlbm-admin-booking-information-item">
                        <span class='tlbm-admin-booked-calendar-values'><?php echo DateTimeTools::FormatWithTime($booking->calendar_slots[0]->timestamp) ?></span><br>
                        <span class='tlbm-admin-booking-values'><a href="<?php echo get_edit_post_link($booking->calendar_slots[0]->booked_calendar_id); ?>"><?php echo $mainvalues->GetCalendarName(); ?></a></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php
    }
}