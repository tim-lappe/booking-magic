<?php


namespace TLBM\Request;

use TLBM\Booking\BookingManager;
use TLBM\Booking\BookingProcessing;
use TLBM\Booking\MainValues;
use TLBM\Email\Contracts\MailSenderInterface;
use TLBM\Form\Contracts\FormManagerInterface;
use TLBM\Output\FrontendMessenger;

if ( !defined('ABSPATH')) {
    return;
}


class CompleteBookingRequest extends RequestBase
{
    public bool $booking_successed = false;
    public bool $error = false;

    /**
     * @var FormManagerInterface
     */
    private FormManagerInterface $formManager;

    /**
     * @var MailSenderInterface
     */
    private MailSenderInterface $mailSender;

    public function __construct(FormManagerInterface $formManager, MailSenderInterface $mailSender)
    {
        parent::__construct();

        $this->mailSender  = $mailSender;
        $this->formManager = $formManager;
        $this->action      = "dobooking";
        $this->hasContent  = true;
    }

    public function onAction()
    {
        $vars = $this->getVars();
        $verifyed = wp_verify_nonce($vars['_wpnonce'], "dobooking_action");
        if (isset($vars['form']) && intval($vars['form']) > 0 && $verifyed) {
            $form_id = $vars['form'];
            $form    = $this->formManager->getForm($form_id);
            if ($form) {
                $booking_processing = new BookingProcessing($vars, $form);
                $not_filled_dps     = $booking_processing->Validate();
                if (sizeof($not_filled_dps) == 0) {
                    $booking        = $booking_processing->GetProcessedBooking();
                    $mainvals       = new MainValues($booking);
                    $booking->title = $mainvals->getBookingTitle();
                    BookingManager::SetBooking($booking);

                    if ($booking->booking_values['contact_email']) {
                        $vars = array();
                        foreach ($booking->booking_values as $value) {
                            $vars[$value->key] = $value->value;
                        }
                        $this->mailSender->sendTemplate(
                            $booking->booking_values['contact_email']->value, "email_booking_confirmation", $vars
                        );
                    }

                    $this->booking_successed = true;
                } else {
                    FrontendMessenger::addMessage(__("Not all required fields were filled out", TLBM_TEXT_DOMAIN));
                    $this->hasContent = false;
                }
            }
        } else {
            $this->error = true;
        }
    }

    public function getContent(): string
    {
        $vars = $this->getVars();
        if ($this->booking_successed === true) {
            return "<h2>Die Buchung ist erfolgreich eingegangen.</h2><p>Sie erhalten in kürze eine Bestätigungsmail</p>";
        } elseif ($this->error) {
            return "<h2>Es ist ein Fehler aufgetreten</h2><p>Ihre Buchung konnte nicht bearbeitet werden, da ein unbekannter Fehler aufgetreten ist</p>" . "<p>" . $this->booking_successed . "</p>";
        } else {
            return "";
        }
    }
}