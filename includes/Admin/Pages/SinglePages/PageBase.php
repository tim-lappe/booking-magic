<?php


namespace TL_Booking\Admin\Pages\SinglePages;


abstract class PageBase {

	public $menu_title;

	public $menu_secondary_title;

	public $capabilities = "manage_options";

	public $menu_slug = "";

	public $icon = "dashicons-calendar";

	public $parent_slug = "";

	public function __construct($menu_title, $menu_slug) {
		$this->menu_title = $menu_title;
		$this->menu_slug = $menu_slug;
	}

	public abstract function ShowPageContent();
}