<?php


namespace TLBM\Entity;


trait IndexedTable
{

    /**
     * @var int
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\GeneratedValue
     * @Doctrine\ORM\Mapping\Column(type="integer", nullable=false)
     */
    protected int $id = 0;

    /**
     * @return int
     */
    public function GetId(): int
    {
        return $this->id;
    }

    public function SetId(int $id)
    {
        $this->id = $id;
    }
}