<?php


namespace TLBM\Entity\Traits;


trait IndexedEntity
{

    /**
     * @var ?int
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\GeneratedValue
     * @Doctrine\ORM\Mapping\Column(type="integer", nullable=false)
     */
    protected ?int $id = null;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id)
    {
        $this->id = $id;
    }
}