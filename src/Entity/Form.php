<?php


namespace TLBM\Entity;

use JsonSerializable;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="forms")
 */
class Form extends ManageableEntity implements JsonSerializable
{

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string", nullable=false, unique=true)
     */
    protected string $title = "";

    /**
     * @var mixed
     * @Doctrine\ORM\Mapping\Column (type="json", nullable=false)
     */
    protected $formData = null;

    public function __construct()
    {
        parent::__construct();
    }

    public function jsonSerialize(): array
    {
        return array(
            "title"         => $this->getTitle(),
            "form_data"     => $this->getFormData(),
        );
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

    public function getFormData()
    {
        return $this->formData;
    }

    /**
     * @param mixed $formData
     */
    public function setFormData($formData): void
    {
        $this->formData = $formData;
    }
}