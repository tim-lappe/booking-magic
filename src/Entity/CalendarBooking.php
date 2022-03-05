<?php


namespace TLBM\Entity;

use JsonSerializable;
use TLBM\Entity\Traits\IndexedEntity;
use TLBM\Utilities\ExtendedDateTime;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="calendar_bookings")
 */
class CalendarBooking implements JsonSerializable
{

    use IndexedEntity;

    /**
     * @var ?Booking
     * @Doctrine\ORM\Mapping\ManyToOne (targetEntity=Booking::class)
     */
    protected ?Booking $booking;

    /**
     * @var ?Calendar
     * @Doctrine\ORM\Mapping\ManyToOne (targetEntity=Calendar::class)
     * @Doctrine\ORM\Mapping\JoinColumn(onDelete="SET NULL")
     */
    protected ?Calendar $calendar;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string", nullable=false)
     */
    protected string $nameFromForm = "";

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string", nullable=false)
     */
    protected string $titleFromForm = "";

    /**
     * @var ?int
     * @Doctrine\ORM\Mapping\Column (type="bigint", nullable=true)
     */
    protected ?int $toTimestamp = 0;

    /**
     * @var bool
     * @Doctrine\ORM\Mapping\Column (type="boolean", nullable=false)
     */
    protected bool $toFullDay = true;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="bigint", nullable=false)
     */
    protected int $fromTimestamp = 0;

    /**
     * @var bool
     * @Doctrine\ORM\Mapping\Column (type="boolean", nullable=false)
     */
    protected bool $fromFullDay = true;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column (type="bigint", nullable=false)
     */
    protected int $slots = 1;

    /**
     * @param Booking|null $booking
     * @param Calendar|null $calendar
     * @param string $nameFromForm
     * @param string $titleFromForm
     * @param int $fromTimestamp
     * @param bool $fromFullDay
     * @param int|null $toTimestamp
     * @param bool $toFullDay
     * @param int $slots
     */
    public function __construct
    (
        ?Booking $booking = null,
        ?Calendar $calendar = null,
        string $nameFromForm = "",
        string $titleFromForm = "",
        int $fromTimestamp = 0,
        bool $fromFullDay = true,
        ?int $toTimestamp = 0,
        bool $toFullDay = true,
        int $slots = 0
    )
    {
        $this->booking = $booking;
        $this->calendar = $calendar;
        $this->nameFromForm = $nameFromForm;
        $this->titleFromForm = $titleFromForm;
        $this->fromTimestamp = $fromTimestamp;
        $this->fromFullDay = $fromFullDay;
        $this->toTimestamp = $toTimestamp;
        $this->toFullDay = $toFullDay;
        $this->slots = $slots;
    }

    public function getBooking(): Booking
    {
        return $this->booking;
    }

    public function setBooking(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * @return ?Calendar
     */
    public function getCalendar(): ?Calendar
    {
        return $this->calendar;
    }

    /**
     * @param ?Calendar $calendar
     */
    public function setCalendar(?Calendar $calendar): void
    {
        $this->calendar = $calendar;
    }

    /**
     * @return string
     */
    public function getNameFromForm(): string
    {
        return $this->nameFromForm;
    }

    /**
     * @param string $nameFromForm
     */
    public function setNameFromForm(string $nameFromForm): void
    {
        $this->nameFromForm = $nameFromForm;
    }

    /**
     * @return string
     */
    public function getTitleFromForm(): string
    {
        return $this->titleFromForm;
    }

    /**
     * @param string $titleFromForm
     */
    public function setTitleFromForm(string $titleFromForm): void
    {
        $this->titleFromForm = $titleFromForm;
    }

    /**
     * @return int
     */
    public function getSlots(): int
    {
        return $this->slots;
    }

    /**
     * @param int $slots
     */
    public function setSlots(int $slots): void
    {
        $this->slots = $slots;
    }

    /**
     * @return bool
     */
    public function isFromFullDay(): bool
    {
        return $this->fromFullDay;
    }

    /**
     * @param bool $fromFullDay
     */
    public function setFromFullDay(bool $fromFullDay): void
    {
        $this->fromFullDay = $fromFullDay;
    }


    /**
     * @return int
     */
    public function getFromTimestamp(): int
    {
        return $this->fromTimestamp;
    }

    /**
     * @param int $fromTimestamp
     */
    public function setFromTimestamp(int $fromTimestamp): void
    {
        $this->fromTimestamp = $fromTimestamp;
    }

    /**
     * @return bool|null
     */
    public function isToFullDay(): ?bool
    {
        return $this->toFullDay;
    }

    /**
     * @param bool $toFullDay
     */
    public function setToFullDay(bool $toFullDay): void
    {
        $this->toFullDay = $toFullDay;
    }

    /**
     * @return ?int
     */
    public function getToTimestamp(): ?int
    {
        return $this->toTimestamp;
    }

    /**
     * @param int|null $toTimestamp
     */
    public function setToTimestamp(?int $toTimestamp): void
    {
        $this->toTimestamp = $toTimestamp;
    }

    /**
     * @return ?ExtendedDateTime
     */
    public function getToDateTime(): ?ExtendedDateTime
    {
        if($this->getToTimestamp()) {
            $dateTime = new ExtendedDateTime($this->getToTimestamp());
            $dateTime->setFullDay($this->isToFullDay());

            return $dateTime;
        }

        return null;
    }

    /**
     * @return ExtendedDateTime
     */
    public function getFromDateTime(): ExtendedDateTime
    {
        $dateTime = new ExtendedDateTime($this->getFromTimestamp());
        $dateTime->setFullDay($this->isFromFullDay());
        return $dateTime;
    }


    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "calendarId" => $this->calendar->getId(),
            "name" => $this->nameFromForm,
            "title" => $this->titleFromForm,
            "fromFullDay" => $this->fromFullDay,
            "fromTimestamp" => $this->fromTimestamp,
            "toFullDay" => $this->toFullDay,
            "toTimestamp" => $this->toTimestamp,
            "slots" => $this->slots
        ];
    }
}