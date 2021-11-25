<?php


namespace TLBM\Entity;

use Doctrine\ORM\Mapping as OrmMapping;

/**
 * Class Calendar
 * @package TLBM\Entity
 * @OrmMapping\Entity
 * @OrmMapping\Table(name="forms")
 */

class Form {

	use IndexedTable;

	/**
	 * @var string
	 * @OrmMapping\Column (type="string", nullable=false, unique=true)
	 */
	protected string $title;

	/**
	 * @var int
	 * @OrmMapping\Column(type="bigint", nullable=false)
	 */
	protected int $timestamp_created;

	/**
	 * @var string
	 * @OrmMapping\Column (type="text", nullable=false)
	 */
	protected string $frontend_html;

	/**
	 * @var string
	 * @OrmMapping\Column (type="json", nullable=false)
	 */
	protected string $form_data;

	/**
	 * @return string
	 */
	public function GetTitle(): string {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function SetTitle( string $title ): void {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function GetFrontendHtml(): string {
		return $this->frontend_html;
	}

	/**
	 * @param string $frontend_html
	 */
	public function SetFrontendHtml( string $frontend_html ): void {
		$this->frontend_html = $frontend_html;
	}

	/**
	 * @return string
	 */
	public function GetFormData(): string {
		return $this->form_data;
	}

	/**
	 * @param string $form_data
	 */
	public function SetFormData( string $form_data ): void {
		$this->form_data = $form_data;
	}
}