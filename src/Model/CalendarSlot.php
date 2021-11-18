<?php


namespace TLBM\Model;


use TLBM\Admin\FormEditor\FormElements\FormElem;

class CalendarSlot {

	/**
	 * @var int
	 */
	public int $timestamp = 0;

	/**
	 * @var int
	 */
	public int $calendar_selection_id = 0;

	/**
	 * @var int
	 */
	public int $booked_calendar_id = 0;

	/**
	 * @var string
	 */
	public string $name = "";

	/**
	 * @var string
	 */
	public string $title = "";

	/**
	 * @var int
	 */
	public int $form_id = 0;
}