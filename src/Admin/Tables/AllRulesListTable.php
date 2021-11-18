<?php


namespace TLBM\Admin\Tables;


use TLBM\Calendar\CalendarManager;
use TLBM\Model\Rule;
use TLBM\Rules\RulesManager;

class AllRulesListTable extends TableBase {

	public function __construct() {
		parent::__construct(__("Rules", TLBM_TEXT_DOMAIN), __("Rule", TLBM_TEXT_DOMAIN));
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

		$filteredbookings = array();
		$rules = RulesManager::GetAllRules($pt_args, $orderby, $order);
		return $rules;
	}

	/**
	 * @param Rule|object $item
	 */
	public function column_calendars($item) {
		$selection = $item->calendar_selection;
		if($selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
			echo __("All", TLBM_TEXT_DOMAIN);
		} else if($selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
			foreach($selection->selected_calendar_ids as $key => $id) {
				$cal = CalendarManager::GetCalendar($id);
				$link = get_edit_post_link( $id );
				if($key > 0) {
					echo ", ";
				}
				echo "<a href='" . $link . "'>" . $cal->title . "</a>";
			}
		} else if($selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
			echo __("All but ", TLBM_TEXT_DOMAIN);
			foreach($selection->selected_calendar_ids as $key => $id) {
				$cal = CalendarManager::GetCalendar($id);
				$link = get_edit_post_link( $id );
				if($key > 0) {
					echo ", ";
				}
				echo "<a href='" . $link . "'><s>" . $cal->title . "</s></a>";
			}
		}
	}

	/**
	 * @param Rule|object $item
	 */
	public function column_title(Rule $item) {
		$link = get_edit_post_link($item->wp_post_id);
		if(!empty($item->title)) {
			echo "<strong><a href='" . $link . "'>" . $item->title . "</a></strong>";
		} else {
			echo "<strong><a href='" . $link . "'>" . $item->wp_post_id . "</a></strong>";
		}
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
			"calendars" => __('Calendars', TLBM_TEXT_DOMAIN),
			"priority" => __('Priority', TLBM_TEXT_DOMAIN)
		);
	}

	protected function GetSortableColumns(): array {
		return array(
			'title' => array('title', true),
			'priority' => array('priority', true)
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

	/**
	 * @param Rule $item
	 *
	 * @return int
	 */
	protected function GetItemId( $item ): int {
		return $item->wp_post_id;
	}

	/**
	 * @return int
	 */
	protected function GetTotalItemsCount(): int {
		return RulesManager::GetAllRulesCount();
	}
}