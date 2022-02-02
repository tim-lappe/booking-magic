<?php


namespace TLBM\Request;


use phpDocumentor\Reflection\Types\This;
use TLBM\Booking\BookingProcessing;
use TLBM\Booking\BookingProcessor;
use TLBM\Form\Contracts\FormManagerInterface;
use TLBM\Output\Contracts\FrontendMessengerInterface;
use TLBM\Utilities\Contracts\DateTimeToolsInterface;

class ShowBookingOverview extends RequestBase
{

    /**
     * @var FormManagerInterface
     */
    private FormManagerInterface $formManager;

    /**
     * @var DateTimeToolsInterface
     */
    private DateTimeToolsInterface $dateTimeTools;

    /**
     * @var FrontendMessengerInterface
     */
    private FrontendMessengerInterface $frontendMessenger;

    public function __construct(FormManagerInterface $formManager, DateTimeToolsInterface $dateTimeTools, FrontendMessengerInterface $frontendMessenger)
    {
        parent::__construct();
        $this->action        = "showbookingoverview";
        $this->formManager   = $formManager;
        $this->dateTimeTools = $dateTimeTools;
        $this->frontendMessenger = $frontendMessenger;
    }

    public function onAction()
    {
        $vars = $this->getVars();
        $verified = wp_verify_nonce($vars['_wpnonce'], "showbookingoverview_action");
        if (isset($vars['form']) && intval($vars['form']) > 0 && $verified) {
            $bookingProcessor = BookingProcessor::createFromVars($vars);
            $invalidFields   = $bookingProcessor->validate();
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
            } else {
                $this->hasContent = true;
            }
        }
    }

    public function getContent(): string
    {
        $vars = $this->getVars();
        $verified = wp_verify_nonce($vars['_wpnonce'], "showbookingoverview_action");
        if (isset($vars['form']) && intval($vars['form']) > 0 && $verified) {
            $bookingProcessor = BookingProcessor::createFromVars($vars);
            $invalidFormData  = $bookingProcessor->validate();
            if (count($invalidFormData) == 0) {
                $html               = "<h2>" . __("Booking overview", TLBM_TEXT_DOMAIN) . "</h2>";
                $booking_values     = $booking_processing->ReadBookingValues();
                $booking            = $booking_processing->GetProcessedBooking();

                $html .= "<form action='" . $_SERVER['REQUEST_URI'] . "' method='post'>";
                $html .= "<div class='tlbm-booking-overview-box'><div class='tlbm-formular-content'>";

                if (isset($booking_values["first_name"]) && isset($booking_values["last_name"])) {
                    $html .= "<h3 class='tlbm-overview-section-title'>" . __("Name", TLBM_TEXT_DOMAIN) . "</h3>";
                    $html .= "<span>" . $booking_values["first_name"]->value . "</span>&nbsp;<span>" . $booking_values["last_name"]->value . "</span>";
                }

                if (isset($booking_values["address"]) && isset($booking_values["zip"]) && isset($booking_values["city"])) {
                    $html .= "<h3 class='tlbm-overview-section-title'>" . __("Address", TLBM_TEXT_DOMAIN) . "</h3>";
                    $html .= "<span>" . $booking_values["address"]->value . "</span><br>";
                    $html .= "<span>" . $booking_values["zip"]->value . "</span>&nbsp;<span>" . $booking_values["city"]->value . "</span>";
                }

                if (isset($booking_values["contact_email"])) {
                    $html .= "<h3 class='tlbm-overview-section-title'>" . __("E-Mail", TLBM_TEXT_DOMAIN) . "</h3>";
                    $html .= "<span>" . $booking_values["contact_email"]->value . "</span>";
                }

                $html .= "</div><div class='tlbm-formular-booked-calendar'>";

                if (sizeof($booking->calendar_slots) >= 1) {
                    $html .= "<h3 class='tlbm-overview-section-title'>" . __("Booked time", TLBM_TEXT_DOMAIN) . "</h3>";
                    $html .= $this->dateTimeTools->formatWithTime($booking->calendar_slots[0]->timestamp);
                }

                $html .= "</div></div>";

                $echovars = array_diff_key($vars, array("_wpnonce" => 0, "form" => 0));
                foreach ($echovars as $key => $value) {
                    $html .= "<input type='hidden' name='" . $key . "' value='" . $value . "'>";
                }

                $html .= "<input type='hidden' name='action' value='dobooking'>";
                $html .= "<input type='hidden' name='form' value='" . $form_id . "'>";
                $html .= wp_nonce_field("dobooking_action", "_wpnonce", true, false);
                $html .= "<button class='tlbm-book-now-btn'>" . __("Book Now", TLBM_TEXT_DOMAIN) . "</button>";
                $html .= "</form>";

                return $html;
            }
        }

        return "hallo";
    }
}