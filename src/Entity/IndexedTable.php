<?php


namespace TLBM\Entity;

use Doctrine\ORM\Mapping as OrmMapping;


trait IndexedTable {

	/**
	 * @var int
	 * @OrmMapping\Id
	 * @OrmMapping\GeneratedValue
	 * @OrmMapping\Column(type="integer", nullable=false)
	 */
	protected int $id;

	/**
	 * @return int
	 */
	public function GetId(): int {
		return $this->id;
	}

    public function SetId(int $id) {
        $this->id = $id;
    }
}