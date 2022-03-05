<?php


namespace TLBM\Request;

use Throwable;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\SinglePageBooking;
use TLBM\Admin\Settings\SingleSettings\Emails\EmailBookingConfirmation;
use TLBM\Booking\BookingProcessor;
use TLBM\Booking\Contracts\CalendarBookingManagerInterface;
use TLBM\Booking\Semantic\BookingValueSemantic;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Email\Contracts\MailSenderInterface;
use TLBM\MainFactory;
use TLBM\Output\Contracts\FrontendMessengerInterface;
use TLBM\Output\SemanticFrontendMessenger;
use TLBM\Repository\Contracts\BookingRepositoryInterface;

use function DI\value;

if ( !defined('ABSPATH')) {
    return;
}


class CompleteBookingRequest extends RequestBase
{
    public bool $bookingSuccessed = false;
    public bool $error = false;

    /**
     * @var BookingRepositoryInterface
     */
    private BookingRepositoryInterface $bookingManager;

    /**
     * @var MailSenderInterface
     */
    private MailSenderInterface $mailSender;

    /**
     * @var CalendarBookingManagerInterface
     */
    private CalendarBookingManagerInterface $calendarBookingManager;

    /**
     * @var SemanticFrontendMessenger
     */
    private SemanticFrontendMessenger $frontendMessenger;

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    private SettingsManagerInterface $settingsManager;

    public function __construct
    (
        LocalizationInterface $localization,
        SemanticFrontendMessenger $frontendMessenger,
        CalendarBookingManagerInterface $calendarBookingManager,
        BookingRepositoryInterface $bookingManager,
        MailSenderInterface $mailSender,
        SettingsManagerInterface $settingsManager
    )
    {
        parent::__construct($localization);

        $this->settingsManager = $settingsManager;
        $this->localization = $localization;
        $this->calendarBookingManager = $calendarBookingManager;
        $this->mailSender  = $mailSender;
        $this->bookingManager = $bookingManager;
        $this->frontendMessenger = $frontendMessenger;
        $this->action      = "dobooking";
        $this->hasContent  = true;
    }

    public function onAction()
    {
        $vars = $this->getVars();
        if (wp_verify_nonce($vars['_wpnonce'], "dobooking_action")) {
            $isOnePage = $this->settingsManager->getValue(SinglePageBooking::class);
            $pendingBooking = null;

            if($isOnePage != "on") {
                $pendingBookingId = intval($vars['pending_booking']);
                $pendingBooking   = $this->bookingManager->getBooking($pendingBookingId);
            }

            if($isOnePage == "on") {
                $bookingProcessor = MainFactory::create(BookingProcessor::class);
                $bookingProcessor->setVars($vars);
                $invalidFields   = $bookingProcessor->validateVars();

                if(count($invalidFields) > 0) {
                    $this->frontendMessenger->addMissingRequiredFieldsMessage($invalidFields);
                    $this->hasContent = false;
                    return;
                }

                $pendingBooking = $bookingProcessor->reserveBooking();
            }

            if($pendingBooking) {
                try {
                    if(count($this->calendarBookingManager->areValidCalendarBookings($pendingBooking->getCalendarBookings()->toArray())) == 0) {
                        $pendingBooking->setInternalState(TLBM_BOOKING_INTERNAL_STATE_COMPLETED);
                        $this->bookingManager->saveBooking($pendingBooking);

                        $semantic = MainFactory::create(BookingValueSemantic::class);
                        $semantic->setValuesFromBooking($pendingBooking);

                        $this->mailSender->sendTemplate($semantic->getContactEmail(), EmailBookingConfirmation::class);
                        $this->bookingSuccessed = true;
                    } else {
                        $this->hasContent = false;
                        $this->frontendMessenger->addMessage($this->localization->__("Booking could not be completed. Some booking times are no longer available ", TLBM_TEXT_DOMAIN));
                    }

                    return;
                } catch (Throwable $exception) {
                    if(WP_DEBUG) {
                        echo $exception->getMessage();
                    }
                }
            }
        }

        $this->error = true;
    }

    public function getContent(): string
    {
        //TODO: Implement booking completed content
        if ($this->bookingSuccessed === true) {
            return "<h2>Die Buchung ist erfolgreich eingegangen.</h2><p>Sie erhalten in Kürze eine Bestätigungsmail</p>";
        } elseif ($this->error) {
            return "<h2>Es ist ein Fehler aufgetreten</h2><p>Ihre Buchung konnte nicht bearbeitet werden, da ein unbekannter Fehler aufgetreten ist</p>";
        } else {
            return "";
        }
    }
}