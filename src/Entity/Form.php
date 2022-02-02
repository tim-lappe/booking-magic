<?php


namespace TLBM\Entity;

use JsonSerializable;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="forms")
 */
class Form implements JsonSerializable
{

    use IndexedTable;

    /**
     * @var string
     * @Doctrine\ORM\Mapping\Column (type="string", nullable=false, unique=true)
     */
    protected string $title;

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Column(type="bigint", nullable=false)
     */
    protected int $tstampCreated;

    /**
     * @var mixed
     * @Doctrine\ORM\Mapping\Column (type="json", nullable=false)
     */
    protected $form_data;

    public function __construct()
    {
        $this->tstampCreated = time();
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
        return $this->form_data;
    }

    /**
     * @param mixed $form_data
     */
    public function setFormData($form_data): void
    {
        $this->form_data = $form_data;
    }
}