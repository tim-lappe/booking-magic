<?php


namespace TLBM\Admin\Tables;


use TLBM\Booking\BookingManager;
use TLBM\Calendar\CalendarManager;
use TLBM\Model\Booking;
use TLBM\Utilities\DateTimeTools;

class BookingListTable extends TableBase {
	public function __construct() {
		parent::__construct(__("Bookings", TLBM_TEXT_DOMAIN), __("Booking", TLBM_TEXT_DOMAIN));
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
				}
			}
		}
	}

	protected function GetItems( $orderby, $order ): array {
		$pt_args = array();
		if(isset($_REQUEST['filter']) && $_REQUEST['filter'] == "trashed") {
			$pt_args = array("post_status" => "trash");
		}

		$filteredbookings = array();
		$bookings = BookingManager::GetAllBookings($pt_args, $orderby, $order);
		foreach ($bookings as $booking) {
			$add = sizeof($booking->calendar_slots) == 0 && (!isset($_REQUEST['calendar-filter']) || empty($_REQUEST['calendar-filter']));
			if(!$add) {
				foreach ( $booking->calendar_slots as $slot ) {
					if (!isset($_REQUEST['calendar-filter']) || empty( $_REQUEST['calendar-filter'] ) || $slot->booked_calendar_id == $_REQUEST['calendar-filter'] ) {
						$add = true;
					}
				}
			}

			if($add) {
				$filteredbookings[] = $booking;
			}
		}

		return $filteredbookings;
	}

	protected function GetViews(): array {
		return array(
			"all" => __("All", TLBM_TEXT_DOMAIN),
			"open" => __("Open", TLBM_TEXT_DOMAIN),
			"trashed" => __("Trash", TLBM_TEXT_DOMAIN)
		);
	}

	protected function GetColumns(): array {
		return array(
			"cb"    => "<input type='checkbox' />",
			"title" => __('Title', TLBM_TEXT_DOMAIN),
			"calendar" => __('Calendar', TLBM_TEXT_DOMAIN),
			"state" => __('State', TLBM_TEXT_DOMAIN),
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
				'delete_permanently' => __( 'Delete permanently', TLBM_TEXT_DOMAIN )
			);
		} else {
			return array(
				'delete' => __( 'Move to trash', TLBM_TEXT_DOMAIN )
			);
		}
	}

	/**
	 * @param Booking $item
	 *
	 * @return int
	 */
	protected function GetItemId( $item ): int {
		return $item->wp_post_id;
	}

	function extra_tablenav( $which ) {
		if ( $which == "top" ){
			$calendars = CalendarManager::GetAllCalendars();
			?>
			<div class="alignleft actions bulkactions">
				<select name="calendar-filter">
					<option value=""><?php _e("All Calendars", TLBM_TEXT_DOMAIN); ?></option>
					<?php foreach ($calendars as $calendar): ?>
						<option <?php echo selected($_REQUEST['calendar-filter'], $calendar->wp_post_id) ?> value="<?php echo $calendar->wp_post_id ?>"><?php echo $calendar->title ?></option>
					<?php endforeach; ?>
				</select>
				<button class="button">Filter</button>
			</div>
			<?php
		}
	}


	/**
	 * @param Booking|object $item
	 */
	public function column_title($item) {
		$link = get_edit_post_link($item->wp_post_id);
		echo "<strong><a href='".$link."'># ".$item->wp_post_id."</a></strong>";
	}

	/**
	 * @param Booking|object $item
	 */
	public function column_datetime($item) {
		$post = get_post($item->wp_post_id);
		echo DateTimeTools::FormatWithTime(strtotime($post->post_date));
	}

	/**
	 * @param Booking|object $item
	 */
	public function column_calendar($item) {
		$calslots = $item->calendar_slots;
		foreach ($calslots as $calendar_slot) {
			$calendar = CalendarManager::GetCalendar($calendar_slot->booked_calendar_id);
			if($calendar) {
				$link = get_edit_post_link( $calendar_slot->booked_calendar_id );
				echo "<a href='" . $link . "'>" . $calendar->title . "</a>&nbsp;&nbsp;&nbsp;" . DateTimeTools::FormatWithTime( $calendar_slot->timestamp ) . "<br>";
			} else {
			    echo "<strong>" . __("Calendar deleted", TLBM_TEXT_DOMAIN) . "</strong>";
			}
		}

		if(sizeof($calslots) == 0) {
			echo "-";
		}
	}

	/**
	 * @param Booking $item
	 */
	public function column_state($item) {
		echo "<div class='tlbm-table-list-state'><strong>Das ist ein Test</strong></div>";
	}

	/**
	 * @param Booking $item
	 *
	 * @return string|void
	 */
	public function column_cb($item): string {
		return '<input type="checkbox" name="wp_post_ids[]" value="'.$item->wp_post_id.'" />';
	}

}