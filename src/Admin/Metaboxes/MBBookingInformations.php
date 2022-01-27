<?php


namespace TLBM\Admin\Metaboxes;


use TLBM\Booking\BookingManager;
use TLBM\Booking\MainValues;
use WP_Post;

class MBBookingInformations extends MetaBoxBase
{

    public function GetOnPostTypes(): array
    {
        return array(TLBM_PT_BOOKING);
    }

    public function RegisterMetaBox()
    {
        $this->AddMetaBox("booking_informations", "Booking Information");
    }

    public function PrintMetaBox(WP_Post $post)
    {
        $booking    = BookingManager::GetBooking($post->ID);
        $mainvalues = new MainValues($booking);
        ?>
        <div class="tlbm-admin-booking-information">
            <div class="tlbm-admin-booking-information-list">
                <?php
                if ($mainvalues->hasName()) {
                    echo "<div class='tlbm-admin-booking-information-item'>";
                    echo "<span class='tlbm-admin-booking-headline'>" . __("Name", TLBM_TEXT_DOMAIN) . "</span><br>";
                    echo "<p class='tlbm-admin-booking-values'>" . $mainvalues->getFullName() . "</p>";
                    echo "</div>";
                }
                if ($mainvalues->hasAddress()) {
                    echo "<div class='tlbm-admin-booking-information-item'>";
                    echo "<span class='tlbm-admin-booking-headline'>" . __("Address", TLBM_TEXT_DOMAIN) . "</span><br>";
                    echo "<p class='tlbm-admin-booking-values'>" . $mainvalues->getAddress() . "</p>";
                    echo "</div>";
                }
                if ($mainvalues->hasContactEmail()) {
                    echo "<div class='tlbm-admin-booking-information-item'>";
                    echo "<span class='tlbm-admin-booking-headline'>" . __("E-Mail", TLBM_TEXT_DOMAIN) . "</span><br>";
                    echo "<p class='tlbm-admin-booking-values'>" . $mainvalues->getContactEmail() . "</p>";
                    echo "</div>";
                }
                if ($mainvalues->hasCustomValues()) {
                    echo "<div class='tlbm-admin-booking-information-item'>";
                    echo "<span class='tlbm-admin-booking-headline'>" . __(
                            "Additional", TLBM_TEXT_DOMAIN
                        ) . "</span><br>";
                    foreach ($mainvalues->getCustomValues() as $value) {
                        echo "<p class='tlbm-admin-booking-values'><strong>" . $value->title . "</strong><br>" . $value->value . "</p>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>
        <?php
        if ($mainvalues->hasCalendar()): ?>
            <div class="tlbm-admin-booking-information">
                <div class="tlbm-admin-booking-information-list">
                    <?php
                    for ($i = 0; $i < $mainvalues->getCalendarCount(); $i++): ?>
                        <div class="tlbm-admin-booking-information-item">
                            <?php
                            if ($mainvalues->getCalendarCount() > 1): ?>
                                <span class='tlbm-admin-booked-calendar-title'><?php
                                    echo $mainvalues->getCalendarFormName($i) ?></span><br>
                            <?php
                            endif; ?>
                            <span class='tlbm-admin-booked-calendar-values'><?php
                                echo $mainvalues->getCalendarTimeFormat($i) ?></span><br>
                            <span class='tlbm-admin-booking-values'><a href="<?php
                                echo get_edit_post_link($mainvalues->getCalendarId($i)); ?>"><?php
                                    echo $mainvalues->getCalendarName($i); ?></a></span>
                        </div>
                    <?php
                    endfor; ?>
                </div>
            </div>
        <?php
        endif; ?>
        <?php
    }
}