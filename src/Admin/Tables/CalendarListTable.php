<?php


namespace TLBM\Admin\Tables;


use TLBM\Booking\BookingManager;
use TLBM\Calendar\CalendarManager;
use TLBM\Model\Calendar;
use TLBM\Utilities\DateTimeTools;

class CalendarListTable extends TableBase {

	public function __construct() {
		parent::__construct(__("Calendars", TLBM_TEXT_DOMAIN), __("Calendar", TLBM_TEXT_DOMAIN));
	}

	protected function ProcessBuldActions() {
		if(isset($_REQUEST['wp_post_ids'])) {
			$ids = $_REQUEST['wp_post_ids'];
			$action = $this->current_action();
			foreach ( $ids as $id ) {
				if ( $action == "delete" ) {
					wp_update_post( array(
						"ID"          => $id,
						"post_status" => "trash"
					) );
				} else if ( $action == "delete_permanently" ) {
					wp_delete_post( $id );
				} else if ( $action == "restore" ) {
					wp_update_post( array(
						"ID"          => $id,
						"post_status" => "publish"
					));
				}
			}
		}
	}

	protected function GetItems( $orderby, $order ): array {
		$pt_args = array();
		if(isset($_REQUEST['filter']) && $_REQUEST['filter'] == "trashed") {
			$pt_args = array("post_status" => "trash");
		}

		$calendars = CalendarManager::GetAllCalendars($pt_args, $orderby, $order);
		return $calendars;
	}

	protected function GetViews(): array {
		return array(
			"all" => __("All", TLBM_TEXT_DOMAIN),
			"trashed" => __("Trash", TLBM_TEXT_DOMAIN)
		);
	}

	protected function GetColumns(): array {
		return array(
			"cb"    => "<input type='checkbox' />",
			"title" => __('Title', TLBM_TEXT_DOMAIN),
			"datetime" => __('Date', TLBM_TEXT_DOMAIN)
		);
	}

	protected function GetSortableColumns(): array {
		return array(
			'title' => array('title', true),
			'datetime' => array('datetime', true)
		);
	}

	protected function GetBulkActions(): array {
		if(isset($_REQUEST['filter']) && $_REQUEST['filter'] == "trashed") {
			return array(
				'delete_permanently' => __( 'Delete permanently', TLBM_TEXT_DOMAIN ),
				'restore' => __( 'Restore', TLBM_TEXT_DOMAIN )
			);
		} else {
			return array(
				'delete' => __( 'Move to trash', TLBM_TEXT_DOMAIN )
			);
		}
	}

	protected function GetItemId( $item ): int {
		return $item->wp_post_id;
	}

	/**
	 * @param Calendar $item
	 */
	public function column_title($item) {
		$link = get_edit_post_link($item->wp_post_id);
		if ( ! empty( $item->title ) ) {
			echo "<strong><a href='" . $link . "'>" . $item->title . "</a></strong>";
		} else {
			echo "<strong><a href='" . $link . "'>" . $item->wp_post_id . "</a></strong>";
		}
	}

	/**
	 * @param Calendar $item
	 */
	public function column_datetime($item) {
		$p = get_post($item->wp_post_id);
		echo DateTimeTools::FormatWithTime(strtotime($p->post_date));
	}

	/**
	 * @return int
	 */
	protected function GetTotalItemsCount(): int {
		return CalendarManager::GetAllCalendarsCount();
	}
}