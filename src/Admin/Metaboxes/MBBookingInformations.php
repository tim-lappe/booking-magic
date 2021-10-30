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
        $this->AddMetaBox("booking_informations", "Booking Information");
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
                if($mainvalues->HasCustomValues()) {
	                echo "<div class='tlbm-admin-booking-information-item'>";
	                echo "<span class='tlbm-admin-booking-headline'>".__("Additional", TLBM_TEXT_DOMAIN)."</span><br>";
                    foreach ($mainvalues->GetCustomValues() as $value) {
	                    echo "<p class='tlbm-admin-booking-values'><strong>" . $value->title . "</strong><br>" . $value->value  . "</p>";
                    }
	                echo "</div>";
                }
                ?>
            </div>
        </div>
        <?php if($mainvalues->HasCalendar()): ?>
            <div class="tlbm-admin-booking-information">
                <div class="tlbm-admin-booking-information-list">
                    <?php for ($i = 0; $i < $mainvalues->GetCalendarCount(); $i++): ?>
                        <div class="tlbm-admin-booking-information-item">
                            <?php if($mainvalues->GetCalendarCount() > 1): ?>
                                <span class='tlbm-admin-booked-calendar-title'><?php echo $mainvalues->GetCalendarFormName($i) ?></span><br>
                            <?php endif; ?>
                            <span class='tlbm-admin-booked-calendar-values'><?php echo $mainvalues->GetCalendarTimeFormat($i) ?></span><br>
                            <span class='tlbm-admin-booking-values'><a href="<?php echo get_edit_post_link($mainvalues->GetCalendarId($i)); ?>"><?php echo $mainvalues->GetCalendarName($i); ?></a></span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php
    }
}