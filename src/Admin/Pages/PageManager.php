<?php


namespace TLBM\Admin\Pages;


use TLBM\Admin\Pages\SinglePages\BookingMagicRoot;
use TLBM\Admin\Pages\SinglePages\BookingsPage;
use TLBM\Admin\Pages\SinglePages\CalendarPage;
use TLBM\Admin\Pages\SinglePages\FormPage;
use TLBM\Admin\Pages\SinglePages\PageBase;
use TLBM\Admin\Pages\SinglePages\RulesPage;
use TLBM\Admin\Pages\SinglePages\SettingsPage;

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