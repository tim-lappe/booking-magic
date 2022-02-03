<?php


namespace TLBM\Request;


use phpDocumentor\Reflection\Types\This;
use TLBM\Booking\BookingProcessing;
use TLBM\Booking\BookingProcessor;
use TLBM\Form\Contracts\FormManagerInterface;
use TLBM\MainFactory;
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

    /**
     * @var ?BookingProcessor
     */
    private ?BookingProcessor $bookingProcessor;

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
            } else {
                $bookingProcessor->reserveBooking();
                $this->bookingProcessor = $bookingProcessor;
                $this->hasContent = true;
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
                $html .= "<h3 class='tlbm-overview-section-title'>" . __("Name", TLBM_TEXT_DOMAIN) . "</h3>";
                $html .= "<span>" . $semantic->getFirstName() . "</span>&nbsp;<span>" . $semantic->getLastName() . "</span>";

            } elseif ($semantic->hasFirstName()) {
                $html .= "<h3 class='tlbm-overview-section-title'>" . __("Name", TLBM_TEXT_DOMAIN) . "</h3>";
                $html .= "<span>" . $semantic->getFirstName() . "</span>";

            } elseif ($semantic->hasLastName()) {
                $html .= "<h3 class='tlbm-overview-section-title'>" . __("Name", TLBM_TEXT_DOMAIN) . "</h3>";
                $html .= "<span>" . $semantic->getLastName() . "</span>";
            }

            if ($semantic->hasFullAddress()) {
                $html .= "<h3 class='tlbm-overview-section-title'>" . __("Address", TLBM_TEXT_DOMAIN) . "</h3>";
                $html .= "<span>" . $semantic->getAddress() . "</span><br>";
                $html .= "<span>" . $semantic->getZip() . "</span>&nbsp;<span>" . $semantic->getCity() . "</span>";
            } elseif ($semantic->hasZipAndCity()) {
                $html .= "<h3 class='tlbm-overview-section-title'>" . __("City", TLBM_TEXT_DOMAIN) . "</h3>";
                $html .= "<span>" . $semantic->getZip() . "</span>&nbsp;<span>" . $semantic->getCity() . "</span>";
            } elseif ($semantic->hasCity()) {
                $html .= "<h3 class='tlbm-overview-section-title'>" . __("City", TLBM_TEXT_DOMAIN) . "</h3>";
                $html .= "<span>" . $semantic->getCity() . "</span>";
            }


            if ($semantic->hasContactEmail()) {
                $html .= "<h3 class='tlbm-overview-section-title'>" . __("E-Mail", TLBM_TEXT_DOMAIN) . "</h3>";
                $html .= "<span>" . $semantic->getContactEmail() . "</span>";
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

        return "";
    }
}