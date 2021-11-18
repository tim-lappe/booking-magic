<?php


namespace TLBM\Admin\Tables;

abstract class TableBase extends \WP_List_Table {

	public $items_per_page = 10;

	public function __construct($title_plural, $title_singular, $items_per_page = 10) {
		parent::__construct(array(
			"plural" => $title_plural,
			"singular" => $title_singular,
			"screen" => null
		));

		$this->items_per_page = $items_per_page;
	}

	public function process_bulk_action() {
		if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {
			$nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
			$action = 'bulk-' . $this->_args['plural'];
			if ( ! wp_verify_nonce( $nonce, $action ) ) {
				wp_die( 'Security check failed!' );
			}
		}

		$this->ProcessBuldActions();
	}


	public function prepare_items() {
		$orderby = isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : "title";
		$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : "desc";

		$this->process_bulk_action();

		$this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
		$this->items = $this->GetItems($orderby, $order);
		$total = $this->GetTotalItemsCount();

		$this->set_pagination_args(array(
			'total_items' => $total,
			'per_page' => $this->items_per_page,
			'total_pages' => ceil($total/$this->items_per_page)
		));
	}

	protected function get_views(): array {
		$views = array();
		$view_definitions = $this->GetViews();
		$current = !empty($_REQUEST['filter']) ? $_REQUEST['filter'] : 'all';

		foreach ($view_definitions as $key => $title) {
			$class = ($current == $key ? ' class="current"' :'');
			$all_url = add_query_arg('filter', $key);
			$html = $current == $key ? "<input type='hidden' name='filter' value='".$key."'>" : "";
			$html .= "<a href='{$all_url}' {$class} >".$title."</a>";
			$views[$key] = $html;

		}

		return $views;
	}

	public function get_columns(): array {
		return $this->GetColumns();
	}

	public function get_sortable_columns(): array {
		return $this->GetSortableColumns();
	}

	public function get_bulk_actions(): array {
		return $this->GetBulkActions();
	}

	public function column_cb($item): string {
		return '<input type="checkbox" name="wp_post_ids[]" value="'.$this->GetItemId($item).'" />';
	}

	/**
	 * @param object $item
	 * @param string      $column_name
	 */
	public function column_default($item, $column_name) {
		echo $item->{$column_name};
	}


	protected abstract function ProcessBuldActions();

	protected abstract function GetItems($orderby, $order) : array;

	protected abstract function GetTotalItemsCount(): int;

	protected abstract function GetViews(): array;

	protected abstract function GetColumns(): array;

	protected abstract function GetSortableColumns(): array;

	protected abstract function GetBulkActions(): array;

	protected abstract function GetItemId($item): int;
}