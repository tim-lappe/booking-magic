<?php


namespace TL_Booking\Admin\Tables;


use TL_Booking\Form\FormManager;
use TL_Booking\Model\Form;

class FormListTable extends TableBase {

	public function __construct() {
		parent::__construct(__("Forms", TLBM_TEXT_DOMAIN), __("Form", TLBM_TEXT_DOMAIN));
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

		$forms = FormManager::GetAllForms($pt_args, $orderby, $order);
		return $forms;
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
			"title" => __('Title', TLBM_TEXT_DOMAIN)
		);
	}

	protected function GetSortableColumns(): array {
		return array(
			'title' => array('title', true)
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
	 * @param Form $item
	 *
	 * @return int
	 */
	protected function GetItemId( $item ): int {
		return $item->wp_post_id;
	}

	/**
	 * @param Form|object $item
	 */
	public function column_title($item) {
		$post = get_post($item->wp_post_id);
		$link = get_edit_post_link($item->wp_post_id);
		if(!empty($post->post_title)) {
			echo "<strong><a href='" . $link . "'>" . $post->post_title . "</a></strong>";
		} else {
			echo "<strong><a href='" . $link . "'>" . $item->wp_post_id . "</a></strong>";
		}
	}
}