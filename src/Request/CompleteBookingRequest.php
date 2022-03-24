<?php


namespace TLBM\Request;

use Throwable;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\SinglePageBooking;
use TLBM\Admin\Settings\SingleSettings\Emails\AdminEmailBookingReceived;
use TLBM\Admin\Settings\SingleSettings\Emails\EmailBookingReceived;
use TLBM\Admin\Settings\SingleSettings\General\AdminMail;
use TLBM\Admin\Settings\SingleSettings\Text\TextBookingReceived;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Booking\BookingProcessor;
use TLBM\Booking\Contracts\CalendarBookingManagerInterface;
use TLBM\Booking\Semantic\BookingValueSemantic;
use TLBM\Email\BookingEmailSemantic;
use TLBM\Email\Contracts\MailSenderInterface;
use TLBM\MainFactory;
use TLBM\Output\SemanticFrontendMessenger;
use TLBM\Repository\Contracts\BookingRepositoryInterface;
use TLBM\Session\SessionManager;

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

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    /**
     * @var SessionManager
     */
    private SessionManager $sessionManager;

    public function __construct(
        LocalizationInterface $localization,
        SemanticFrontendMessenger $frontendMessenger,
        CalendarBookingManagerInterface $calendarBookingManager,
        BookingRepositoryInterface $bookingManager,
        MailSenderInterface $mailSender,
        SettingsManagerInterface $settingsManager,
        SessionManager $sessionManager
    )
    {
        parent::__construct($localization);

        $this->settingsManager        = $settingsManager;
        $this->localization           = $localization;
        $this->calendarBookingManager = $calendarBookingManager;
        $this->mailSender             = $mailSender;
        $this->bookingManager         = $bookingManager;
        $this->frontendMessenger      = $frontendMessenger;
        $this->action                 = "dobooking";
        $this->hasContent             = true;
        $this->sessionManager         = $sessionManager;
    }

    public function onAction()
    {
        $vars = $this->getVars();
        if (wp_verify_nonce($vars['_wpnonce'], "dobooking_action")) {
            $isOnePage = $this->settingsManager->getValue(SinglePageBooking::class);
            $pendingBooking = null;

            if($isOnePage != "on") {
                $pendingBookingId = $this->sessionManager->getValue("pendingBookingId");
                if ($pendingBookingId) {
                    $pendingBooking = $this->bookingManager->getBooking($pendingBookingId);
                }
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
                        $this->sessionManager->removeValue("pendingBookingId");
                        $completedBookingIds = $this->sessionManager->getValue("completedBookings");

                        if ( !$completedBookingIds) {
                            $this->sessionManager->setValue("completedBookings", [$vars['_wpnonce'] => $pendingBooking->getId()]);
                        } else {
                            $completedBookingIds[$vars['_wpnonce']] = $pendingBooking->getId();
                            $this->sessionManager->setValue("completedBookings", $completedBookingIds);
                        }

                        $semantic = MainFactory::create(BookingValueSemantic::class);
                        $semantic->setValuesFromBooking($pendingBooking);

                        $emailSemantic = MainFactory::create(BookingEmailSemantic::class);
                        $emailSemantic->setBooking($pendingBooking);
                        $emailSemantic->setBookingSemantic($semantic);

                        $this->mailSender->sendTemplate($semantic->getContactEmail(), EmailBookingReceived::class, $emailSemantic);
                        $this->mailSender->sendTemplate($this->settingsManager->getValue(AdminMail::class), AdminEmailBookingReceived::class, $emailSemantic);
                        $this->bookingSuccessed = true;

                    } else {
                        $this->hasContent = false;
                        $this->frontendMessenger->addMessage($this->localization->__("Booking could not be completed. Some booking times are no longer available ", TLBM_TEXT_DOMAIN));
                    }

                    return;
                } catch (Throwable $exception) {
                    if (WP_DEBUG) {
                        echo $exception->getMessage();
                    }
                }
            }
        }

        $completedBookingIds = $this->sessionManager->getValue("completedBookings");
        if (isset($completedBookingIds[$vars['_wpnonce']])) {
            $this->hasContent       = true;
            $this->error            = false;
            $this->bookingSuccessed = true;

            return;
        }

        $this->error = true;
    }

    public function getContent(): string
    {
        if ($this->bookingSuccessed === true) {
            return $this->settingsManager->getValue(TextBookingReceived::class);
        } elseif ($this->error) {
            //TODO: Implement TextBookingFailed Setting and print here
            return "<h2>Es ist ein Fehler aufgetreten</h2><p>Ihre Buchung konnte nicht bearbeitet werden, da ein unbekannter Fehler aufgetreten ist</p>";
        } else {
            return "";
        }
    }
}