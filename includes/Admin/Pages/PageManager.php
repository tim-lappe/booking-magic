<?php


namespace TL_Booking\Admin\Pages;


use TL_Booking\Admin\Pages\SinglePages\BookingMagicRoot;
use TL_Booking\Admin\Pages\SinglePages\BookingsPage;
use TL_Booking\Admin\Pages\SinglePages\CalendarPage;
use TL_Booking\Admin\Pages\SinglePages\FormPage;
use TL_Booking\Admin\Pages\SinglePages\PageBase;
use TL_Booking\Admin\Pages\SinglePages\RulesPage;
use TL_Booking\Admin\Pages\SinglePages\SettingsPage;

class PageManager {

	/**
	 * @var PageBase[]
	 */
	public static $pages = array();

	private static function LoadPages() {
		self::$pages = array(
			new BookingMagicRoot(),
			new BookingsPage(),
			new CalendarPage(),
			new RulesPage(),
			new FormPage(),
			new SettingsPage()
		);
	}

	public static function RegisterPages() {
		self::LoadPages();

		foreach (self::$pages as $page) {
			if ( empty( $page->parent_slug ) ) {
				add_menu_page( $page->menu_title, $page->menu_title, $page->capabilities, $page->menu_slug, array(
					$page,
					"ShowPageContent"
				), $page->icon, 10 );
				if(!empty($page->menu_secondary_title)) {
					add_submenu_page( $page->menu_slug, $page->menu_secondary_title, $page->menu_secondary_title, $page->capabilities, $page->menu_slug, array(
						$page,
						"ShowPageContent"
					) );
				}
			} else {
				add_submenu_page($page->parent_slug, $page->menu_title,  $page->menu_title, $page->capabilities, $page->menu_slug, array(
					$page,
					"ShowPageContent"
				));
			}
		}
	}
}