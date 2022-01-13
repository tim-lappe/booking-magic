<?php


namespace TLBM\Admin\Tables;


use TLBM\Admin\Pages\SinglePages\FormEditPage;
use TLBM\Form\FormManager;
use TLBM\Entity\Form;

class FormListTable extends TableBase {

	public function __construct() {
		parent::__construct(__("Forms", TLBM_TEXT_DOMAIN), __("Form", TLBM_TEXT_DOMAIN), 10, __("You haven't created any forms yet", TLBM_TEXT_DOMAIN));
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

		return FormManager::GetAllForms($pt_args, $orderby, $order);
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
	 * @param Form $item
	 *
	 * @return int
	 */
	protected function GetItemId( $item ): int {
		return $item->GetId();
	}

	/**
	 * @param Form $item
	 */
	public function column_title($item) {
		$link = FormEditPage::GetEditLink($item->GetId());
		if(!empty($item->GetTitle())) {
			echo "<strong><a href='" . $link . "'>" . $item->GetTitle() . "</a></strong>";
		} else {
			echo "<strong><a href='" . $link . "'>" . $item->GetId() . "</a></strong>";
		}
	}

    /**
     * @return int
     * @throws \Exception
     */

	protected function GetTotalItemsCount(): int {
		return FormManager::GetAllFormsCount();
	}
}