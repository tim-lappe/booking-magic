<?php


namespace TLBM\Entity;

use JsonSerializable;
use TLBM\Entity\Traits\IndexedEntity;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="booking_values")
 */
class BookingValue implements JsonSerializable
{

    use IndexedEntity;

    /**
     * @var ?Booking
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity=Booking::class)
     */
    protected ?Booking $booking;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string", nullable=false)
     */
    protected string $name;

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
     * @param string $name
     * @param string $title
     * @param string $value
     * @param Booking|null $booking
     */
    public function __construct(string $name = "", string $title = "", string $value = "", ?Booking $booking = null)
    {
        $this->name = $name;
        $this->title = $title;
        $this->value = $value;
        $this->booking = $booking;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return Booking
     */
    public function getBooking(): Booking
    {
        return $this->booking;
    }

    public function setBooking(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "value" => $this->value,
            "title" => $this->title,
        ];
    }
}