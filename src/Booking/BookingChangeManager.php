<?php

namespace TLBM\Booking;

use TLBM\Admin\Settings\SingleSettings\Emails\EmailBookingCancelled;
use TLBM\Admin\Settings\SingleSettings\Emails\EmailBookingConfirmation;
use TLBM\Admin\Settings\SingleSettings\Emails\EmailBookingInProcess;
use TLBM\Booking\Semantic\BookingValueSemantic;
use TLBM\Email\BookingEmailSemantic;
use TLBM\Email\Contracts\MailSenderInterface;
use TLBM\Entity\Booking;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;

class BookingChangeManager
{
    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    /**
     * @var Booking
     */
    private Booking $booking;

    /**
     * @var Booking
     */
    private Booking $newBooking;

    /**
     * @var MailSenderInterface
     */
    private MailSenderInterface $mailSender;

    public function __construct(EntityRepositoryInterface $entityRepository, MailSenderInterface $mailSender)
    {
        $this->mailSender       = $mailSender;
        $this->entityRepository = $entityRepository;
    }

    public function setState(string $status)
    {
        $this->newBooking->setState($status);
    }

    public function storeValuesToBooking()
    {
        if ($this->newBooking->getState() != $this->booking->getState()) {
            $semantic = MainFactory::create(BookingValueSemantic::class);
            $semantic->setValuesFromBooking($this->booking);

            $emailSemantic = MainFactory::create(BookingEmailSemantic::class);
            $emailSemantic->setBooking($this->booking);
            $emailSemantic->setBookingSemantic($semantic);

            if ($this->newBooking->getState() == "processing") {
                $this->mailSender->sendTemplate($semantic->getContactEmail(), EmailBookingInProcess::class, $emailSemantic);
            } elseif ($this->newBooking->getState() == "confirmed") {
                $this->mailSender->sendTemplate($semantic->getContactEmail(), EmailBookingConfirmation::class, $emailSemantic);
            } elseif ($this->newBooking->getState() == "cancelled") {
                $this->mailSender->sendTemplate($semantic->getContactEmail(), EmailBookingCancelled::class, $emailSemantic);
            }

            $this->booking->setState($this->newBooking->getState());
        }
    }

    /**
     * @return Booking
     */
    public function getBooking(): Booking
    {
        return $this->booking;
    }

    /**
     * @param Booking $booking
     */
    public function setBooking(Booking $booking): void
    {
        $this->booking    = $booking;
        $this->newBooking = new Booking();
    }
}