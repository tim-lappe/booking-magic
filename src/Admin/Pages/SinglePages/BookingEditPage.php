<?php

namespace TLBM\Admin\Pages\SinglePages;

use TLBM\Entity\Booking;
use TLBM\Entity\ManageableEntity;
use TLBM\Repository\Contracts\BookingRepositoryInterface;

/**
 * @extends EntityEditPage<Booking>
 */
class BookingEditPage extends EntityEditPage
{
    /**
     * @var BookingRepositoryInterface
     */
    private BookingRepositoryInterface $bookingManager;

    public function __construct(BookingRepositoryInterface $bookingManager)
    {
        parent::__construct(__("Booking", TLBM_TEXT_DOMAIN), "booking-edit", "booking-edit", false);
        $this->bookingManager = $bookingManager;
    }

    /**
     * @return void
     */
    public function displayEntityEditForm(): void
    {

    }

    /**
     * @param int $id
     *
     * @return ManageableEntity|null
     */
    protected function getEntityById(int $id): ?ManageableEntity
    {
        return $this->bookingManager->getBooking($id);
    }

    /**
     * @param mixed $vars
     * @param ManageableEntity|null $savedEntity
     *
     * @return array
     */
    protected function onSaveEntity($vars, ?ManageableEntity &$savedEntity): array
    {
        // TODO: Implement onSaveEntity() method.
        return [];
    }
}