<?php


namespace TLBM\Request;

use TLBM\Booking\BookingProcessor;
use TLBM\MainFactory;
use TLBM\Output\Contracts\FrontendMessengerInterface;

class ShowBookingOverview extends RequestBase
{


    /**
     * @var FrontendMessengerInterface
     */
    private FrontendMessengerInterface $frontendMessenger;

    /**
     * @var ?BookingProcessor
     */
    private ?BookingProcessor $bookingProcessor;

    public function __construct(FrontendMessengerInterface $frontendMessenger)
    {
        parent::__construct();
        $this->action        = "showbookingoverview";
        $this->frontendMessenger = $frontendMessenger;
    }

    public function onAction()
    {
        $vars = $this->getVars();
        $verified = wp_verify_nonce($vars['_wpnonce'], "showbookingoverview_action");
        if (isset($vars['form']) && intval($vars['form']) > 0 && $verified) {
            $bookingProcessor = MainFactory::create(BookingProcessor::class);
            $bookingProcessor->setVars($vars);
            $invalidFields   = $bookingProcessor->validateVars();
            if(count($invalidFields) > 0) {
                $errors = [];
                foreach($invalidFields as $field) {
                    $title = $field->getLinkedSettings()->getValue("title");
                    if(!empty($title)) {
                        $errors[] = $title;
                    }
                }

                $this->frontendMessenger->addMessage(__("Not all required fields were filled out: <br>" . implode("<br>", $errors), TLBM_TEXT_DOMAIN));
                $this->hasContent = false;

            } elseif ($bookingProcessor->reserveBooking() != null) {
                $this->bookingProcessor = $bookingProcessor;
                $this->hasContent       = true;

            } else {
                $this->hasContent = false;
                $this->frontendMessenger->addMessage(__("Booking could not be completed. Some booking times are no longer available ", TLBM_TEXT_DOMAIN));
            }
        }
    }

    public function getContent(): string
    {
        $vars = $this->getVars();
        if($this->bookingProcessor != null) {
            $html               = "<h2>" . __("Booking overview", TLBM_TEXT_DOMAIN) . "</h2>";

            $semantic = $this->bookingProcessor->getSemantic();
            $semantic->getFirstName();

            $html .= "<form action='" . $_SERVER['REQUEST_URI'] . "' method='post'>";
            $html .= "<div class='tlbm-booking-overview-box'><div class='tlbm-formular-content'>";

            if ($semantic->hasFullName()) {
                $html .= "<div class='tlbm-overview-section-title'>" . __("Name", TLBM_TEXT_DOMAIN) . "</div>";
                $html .= "<span>" . $semantic->getFirstName() . "</span>&nbsp;<span>" . $semantic->getLastName() . "</span>";

            } elseif ($semantic->hasFirstName()) {
                $html .= "<div class='tlbm-overview-section-title'>" . __("Name", TLBM_TEXT_DOMAIN) . "</div>";
                $html .= "<span>" . $semantic->getFirstName() . "</span>";

            } elseif ($semantic->hasLastName()) {
                $html .= "<div class='tlbm-overview-section-title'>" . __("Name", TLBM_TEXT_DOMAIN) . "</div>";
                $html .= "<span>" . $semantic->getLastName() . "</span>";
            }

            if ($semantic->hasFullAddress()) {
                $html .= "<div class='tlbm-overview-section-title'>" . __("Address", TLBM_TEXT_DOMAIN) . "</div>";
                $html .= "<span>" . $semantic->getAddress() . "</span><br>";
                $html .= "<span>" . $semantic->getZip() . "</span>&nbsp;<span>" . $semantic->getCity() . "</span>";
            } elseif ($semantic->hasZipAndCity()) {
                $html .= "<div class='tlbm-overview-section-title'>" . __("City", TLBM_TEXT_DOMAIN) . "</div>";
                $html .= "<span>" . $semantic->getZip() . "</span>&nbsp;<span>" . $semantic->getCity() . "</span>";
            } elseif ($semantic->hasCity()) {
                $html .= "<div class='tlbm-overview-section-title'>" . __("City", TLBM_TEXT_DOMAIN) . "</div>";
                $html .= "<span>" . $semantic->getCity() . "</span>";
            }


            if ($semantic->hasContactEmail()) {
                $html .= "<div class='tlbm-overview-section-title'>" . __("E-Mail", TLBM_TEXT_DOMAIN) . "</div>";
                $html .= "<span>" . $semantic->getContactEmail() . "</span>";
            }

            $html .= "</div><div class='tlbm-formular-booked-calendar'>";
            $calendarBookings = $this->bookingProcessor->getPendingBooking()->getCalendarBookings();

            if(sizeof($calendarBookings) > 0) {
                //TODO: Hier müssen auch mehrere Buchungszeiten berücksichtigt werden und nicht nur "From"
                $html .= "<div class='tlbm-overview-section-title'>" . __("Selected time", TLBM_TEXT_DOMAIN) . "</div>";
                foreach ($calendarBookings as $calendarBooking) {
                    $html .= $calendarBooking->getFromDateTime() . "<br>";
                    $html .= $calendarBooking->getCalendar()->getTitle();
                }
            }

            $html .= "</div></div>";
            $html .= "<input type='hidden' name='tlbm_action' value='dobooking'>";
            $html .= "<input type='hidden' name='pending_booking' value='" . $this->bookingProcessor->getPendingBooking()->getId() . "'>";
            $html .= wp_nonce_field("dobooking_action", "_wpnonce", true, false);
            $html .= "<button class='tlbm-book-now-btn'>" . __("Book Now", TLBM_TEXT_DOMAIN) . "</button>";
            $html .= "</form>";

            return $html;
        }

        return "";
    }
}