<?php

namespace TLBM\Email;

use TLBM\Booking\Semantic\BookingValueSemantic;
use TLBM\Entity\Booking;

class BookingEmailSemantic extends EmailSemantic
{
    private Booking $booking;
    private BookingValueSemantic $bookingSemantic;

    public function getValues(): array
    {
        $fields                   = $this->bookingSemantic->getValues();
        $fields['booking_fields'] = $this->getBookingDetails();
        $fields['ID']             = $this->booking->getId();

        return $fields;
    }

    /**
     * @return string
     */
    public function getBookingDetails(): string
    {
        $html = "<table>";
        foreach ($this->booking->getBookingValues() as $bookingValue) {
            $html .= "<tr>";
            $html .= "<td>" . $bookingValue->getTitle() . "</td>";
            $html .= "<td><b>" . $bookingValue->getValue() . "</b></td>";
            $html .= "</tr>";
        }
        $html .= "</table>";

        return $html;
    }

    /**
     * @return BookingValueSemantic
     */
    public function getBookingSemantic(): BookingValueSemantic
    {
        return $this->bookingSemantic;
    }

    /**
     * @param BookingValueSemantic $bookingSemantic
     */
    public function setBookingSemantic(BookingValueSemantic $bookingSemantic): void
    {
        $this->bookingSemantic = $bookingSemantic;
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
        $this->booking = $booking;
    }
}