<?php


namespace TLBM\Model;


use TLBM\Admin\FormEditor\FormElements\FormElem;

class CalendarSlot {

	/**
	 * @var int
	 */
	public $timestamp;


	/**
	 * @var
	 */
	public $calendar_selection;

	/**
	 * @var int
	 */
	public $booked_calendar_id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var int
	 */
	public $form_id;
}