<?php


namespace TLBM\Entity;

use Doctrine\ORM\Mapping as OrmMapping;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @OrmMapping\Entity
 * @OrmMapping\Table(name="time_slots")
 */
class TimeSlot {

	use IndexedTable;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer", nullable=false)
	 */
	protected int $from_hour;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer", nullable=false)
	 */
	protected int $from_min;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer", nullable=false)
	 */
	protected int $to_hour;

	/**
	 * @var int
	 * @OrmMapping\Column (type="integer", nullable=false)
	 */
	protected int $to_min;
}