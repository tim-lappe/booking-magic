<?php

namespace TLBM\Booking\Semantic;

class BookingValueSemantic
{

    /**
     * @var array
     */
    private array $values;

    public function __construct()
    {

    }

    public function getFirstName(): string
    {
        return $this->getSingleValue("first_name");
    }

    public function getLastName(): string
    {
        return $this->getSingleValue("last_name");
    }

    public function hasFullAddress(): bool
    {
        return $this->hasSingleValue("zip") && $this->hasSingleValue("city") && $this->hasSingleValue("address");
    }

    public function hasZipAndCity(): bool
    {
        return $this->hasSingleValue("zip") && $this->hasSingleValue("city");
    }

    public function hasCity(): bool
    {
        return $this->hasSingleValue("city");
    }

    public function getCity(): string
    {
        return $this->getSingleValue("city");
    }

    public function getZip(): string
    {
        return $this->getSingleValue("zip");
    }

    public function getAddress(): string
    {
        return $this->getSingleValue("address");
    }

    public function getContactEmail(): string
    {
        return $this->getSingleValue("contact_email");
    }

    public function hasContactEmail(): bool
    {
        return $this->hasSingleValue("contact_email");
    }

    public function hasFullName(): bool
    {
        return $this->hasSingleValue("first_name") || $this->hasSingleValue("last_name");
    }

    public function hasFirstName(): bool
    {
        return $this->hasSingleValue("first_name");
    }

    public function hasLastName(): bool
    {
        return $this->hasSingleValue("last_name");
    }

    public function hasSingleValue(string $name): bool
    {
        return isset($this->values[$name]) && !empty(trim($this->values[$name]));
    }

    public function getSingleValue(string $name): string
    {
        if($this->hasSingleValue($name)) {
            return trim($this->values[$name]);
        }

        return "";
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values): void
    {
        $this->values = $values;
    }
}