<?php


namespace TLBM\Entity;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="booking_values")
 */
class BookingValue
{

    use IndexedTable;

    /**
     * @var Booking
     * @Doctrine\ORM\Mapping\OneToOne(targetEntity=Booking::class)
     */
    protected Booking $booking;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string", nullable=false)
     */
    protected string $key;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="text", nullable=false)
     */
    protected string $value;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string", nullable=false)
     */
    protected string $title;

    /**
     * @return string
     */
    public function GetKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function SetKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function GetValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function SetValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function GetTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function SetTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return Booking
     */
    public function GetBooking(): Booking
    {
        return $this->booking;
    }

    public function SetBooking(Booking $booking)
    {
        $this->booking = $booking;
    }
}