<?php

namespace TLBM\Repository\Contracts;

use TLBM\Entity\Booking;

interface BookingRepositoryInterface
{
    /**
     * @param Booking $booking
     *
     * @return int
     */
    public function saveBooking(Booking $booking): int;

    /**
     * @param array $options
     * @param string $orderby
     * @param string $order
     * @param int $offset
     * @param int $limit
     *
     * @return Booking[]
     */
    public function getAllBookings(array $options = [], string $orderby = "id", string $order = "desc", int $offset = 0, int $limit = 0): array;

    /**
     * @param int $booking_id
     *
     * @return ?Booking
     */
    public function getBooking(int $booking_id): ?Booking;

    /**
     * @return int
     */
    public function getAllBookingsCount(): int;

    /**
     *
     * @return void
     */
    public function cleanExpiredReservedBookings();
}