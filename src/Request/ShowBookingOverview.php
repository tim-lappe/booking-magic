<?php


namespace TLBM\Request;

use TLBM\Admin\Settings\SettingsManager;
use TLBM\Admin\Settings\SingleSettings\Text\TextBookNow;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Booking\BookingProcessor;
use TLBM\MainFactory;
use TLBM\Output\SemanticFrontendMessenger;
use TLBM\Repository\Contracts\BookingRepositoryInterface;
use TLBM\Session\SessionManager;

class ShowBookingOverview extends RequestBase
{


    /**
     * @var SemanticFrontendMessenger
     */
    private SemanticFrontendMessenger $semanticFrontendMessenger;

    /**
     * @var ?BookingProcessor
     */
    private ?BookingProcessor $bookingProcessor;

    /**
     * @var SettingsManager
     */
    private SettingsManager $settingsManager;

    /**
     * @var SessionManager
     */
    private SessionManager $sessionManager;

    /**
     * @var BookingRepositoryInterface
     */
    private BookingRepositoryInterface $bookingRepository;

    /**
     * @param SemanticFrontendMessenger $frontendMessenger
     * @param LocalizationInterface $localization
     * @param SettingsManager $settingsManager
     * @param SessionManager $sessionManager
     */
    public function __construct(
        SemanticFrontendMessenger $frontendMessenger,
        LocalizationInterface $localization,
        SettingsManager $settingsManager,
        SessionManager $sessionManager,
        BookingRepositoryInterface $bookingRepository
    ) {
        parent::__construct($localization);
        $this->bookingRepository         = $bookingRepository;
        $this->action                    = "showbookingoverview";
        $this->semanticFrontendMessenger = $frontendMessenger;
        $this->settingsManager           = $settingsManager;
        $this->sessionManager            = $sessionManager;
    }

    public function onAction()
    {
        $vars = $this->getVars();
        $verified = wp_verify_nonce($vars['_wpnonce'], "showbookingoverview_action");
        if (isset($vars['form']) && intval($vars['form']) > 0 && $verified) {
            $pendingBookingId = $this->sessionManager->getValue("pendingBookingId");
            if (intval($pendingBookingId)) {
                $pendingBooking = $this->bookingRepository->getBooking($pendingBookingId);
                if ($pendingBooking) {
                    $bookingProcessor = MainFactory::create(BookingProcessor::class);
                    $bookingProcessor->setFromPendingBooking($pendingBooking);
                    $this->bookingProcessor = $bookingProcessor;
                    $this->hasContent       = true;

                    return;
                }
            }

            $bookingProcessor = MainFactory::create(BookingProcessor::class);
            $bookingProcessor->setVars($vars);
            $invalidFields = $bookingProcessor->validateVars();

            $formValues = [];
            foreach ($bookingProcessor->getLinkedFormDataFields() as $field) {
                $name              = $field->getLinkedSettings()->getValue("name");
                $formValues[$name] = $field->getInputVarByName($name);
            }

            $this->sessionManager->setValue("lastFormFieldValues", $formValues);

            if (count($invalidFields) > 0) {
                $this->semanticFrontendMessenger->addMissingRequiredFieldsMessage($invalidFields);
                $this->hasContent = false;
            } elseif ($bookingProcessor->reserveBooking() != null) {
                $this->bookingProcessor = $bookingProcessor;
                $this->hasContent       = $this->sessionManager->setValue("pendingBookingId", $bookingProcessor->getPendingBooking()->getId());
                if ( !$this->hasContent) {
                    $this->semanticFrontendMessenger->addMessage($this->localization->getText("An internal error occured!", TLBM_TEXT_DOMAIN));
                }
            } else {
                $this->hasContent = false;
                $this->semanticFrontendMessenger->addMessage($this->localization->getText("Booking could not be completed. Please select a valid time", TLBM_TEXT_DOMAIN));
            }
        }
    }

    public function getContent(): string
    {
        $vars = $this->getVars();
        if($this->bookingProcessor != null) {
            $html = "<h2>" . $this->localization->getText("Booking overview", TLBM_TEXT_DOMAIN) . "</h2>";

            $semantic = $this->bookingProcessor->getSemantic();
            $semantic->getFirstName();

            $html .= "<form action='" . $_SERVER['REQUEST_URI'] . "' method='post'>";
            $html .= "<div class='tlbm-booking-overview-box'><div class='tlbm-formular-content'>";

            if ($semantic->hasFullName()) {
                $html .= "<div class='tlbm-overview-section-title'>" . $this->localization->getText("Name", TLBM_TEXT_DOMAIN) . "</div>";
                $html .= "<span>" . $semantic->getFirstName() . "</span>&nbsp;<span>" . $semantic->getLastName() . "</span>";

            } elseif ($semantic->hasFirstName()) {
                $html .= "<div class='tlbm-overview-section-title'>" . $this->localization->getText("Name", TLBM_TEXT_DOMAIN) . "</div>";
                $html .= "<span>" . $semantic->getFirstName() . "</span>";

            } elseif ($semantic->hasLastName()) {
                $html .= "<div class='tlbm-overview-section-title'>" . $this->localization->getText("Name", TLBM_TEXT_DOMAIN) . "</div>";
                $html .= "<span>" . $semantic->getLastName() . "</span>";
            }

            if ($semantic->hasFullAddress()) {
                $html .= "<div class='tlbm-overview-section-title'>" . $this->localization->getText("Address", TLBM_TEXT_DOMAIN) . "</div>";
                $html .= "<span>" . $semantic->getAddress() . "</span><br>";
                $html .= "<span>" . $semantic->getZip() . "</span>&nbsp;<span>" . $semantic->getCity() . "</span>";
            } elseif ($semantic->hasZipAndCity()) {
                $html .= "<div class='tlbm-overview-section-title'>" . $this->localization->getText("City", TLBM_TEXT_DOMAIN) . "</div>";
                $html .= "<span>" . $semantic->getZip() . "</span>&nbsp;<span>" . $semantic->getCity() . "</span>";
            } elseif ($semantic->hasCity()) {
                $html .= "<div class='tlbm-overview-section-title'>" . $this->localization->getText("City", TLBM_TEXT_DOMAIN) . "</div>";
                $html .= "<span>" . $semantic->getCity() . "</span>";
            }


            if ($semantic->hasContactEmail()) {
                $html .= "<div class='tlbm-overview-section-title'>" . $this->localization->getText("E-Mail", TLBM_TEXT_DOMAIN) . "</div>";
                $html .= "<span>" . $semantic->getContactEmail() . "</span>";
            }

            $html .= "</div><div class='tlbm-formular-booked-calendar'>";
            $calendarBookings = $this->bookingProcessor->getPendingBooking()->getCalendarBookings();

            if(sizeof($calendarBookings) > 0) {
                //TODO: Hier müssen auch mehrere Buchungszeiten berücksichtigt werden und nicht nur "From"
                $html .= "<div class='tlbm-overview-section-title'>" . $this->localization->getText("Selected time", TLBM_TEXT_DOMAIN) . "</div>";
                foreach ($calendarBookings as $calendarBooking) {
                    $html .= $calendarBooking->getFromDateTime() . "<br>";
                }
            }

            $html .= "</div></div>";
            $html .= "<input type='hidden' name='tlbm_action' value='dobooking'>";
            $html .= $this->sessionManager->getFormInputContent();
            $html .= wp_nonce_field("dobooking_action", "_wpnonce", true, false);
            $html .= "<button class='tlbm-book-now-btn'>" . $this->settingsManager->getValue(TextBookNow::class) . "</button>";
            $html .= "</form>";

            return $html;
        }

        return "";
    }
}