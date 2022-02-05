<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Entity\Booking;
use TLBM\Repository\Contracts\BookingRepositoryInterface;

class BookingEditPage extends FormPageBase
{

    private ?Booking $editingBooking;

    /**
     * @var BookingRepositoryInterface
     */
    private BookingRepositoryInterface $bookingManager;

    public function __construct(BookingRepositoryInterface $bookingManager)
    {
        parent::__construct("booking-edit", "booking-edit", false);
        $this->bookingManager = $bookingManager;
    }

    public function showFormPageContent()
    {

    }

    public function onSave($vars): array
    {
        return [];
    }


    /**
     * @param int $bookingId
     *
     * @return string
     */
    public function getEditLink(int $bookingId = -1): string
    {
        if ($bookingId >= 0) {
            return admin_url() . "admin.php?page=" . urlencode($this->menu_slug) . "&booking_id=" . urlencode($bookingId);
        }

        return admin_url() . "admin.php?page=" . urlencode($this->menu_slug);
    }

    protected function getHeadTitle(): string
    {
        return $this->getEditingBooking() == null ? __("Add New Booking", TLBM_TEXT_DOMAIN) : __(
            "Edit Booking", TLBM_TEXT_DOMAIN
        );
    }

    private function getEditingBooking(): ?Booking
    {
        if($this->editingBooking) {
            return $this->editingBooking;
        }

        if (isset($_REQUEST['booking_id'])) {
            return $this->bookingManager->getBooking($_REQUEST['calendar_id']);
        }

        return null;
    }
}