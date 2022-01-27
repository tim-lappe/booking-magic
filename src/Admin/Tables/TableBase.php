<?php


namespace TLBM\Admin\Tables;

use WP_List_Table;

abstract class TableBase extends WP_List_Table
{

    public $items_per_page = 10;

    public $no_items_display = "";

    public function __construct($title_plural, $title_singular, $items_per_page = 10, $no_items_display = "")
    {
        parent::__construct(array(
                                "plural"   => $title_plural,
                                "singular" => $title_singular,
                                "screen"   => null
                            ));

        if (empty($no_items_display)) {
            $no_items_display = __("Nothing to show", TLBM_TEXT_DOMAIN);
        }

        $this->no_items_display = $no_items_display;

        $this->items_per_page = $items_per_page;
    }

    public function prepare_items()
    {
        $orderby = $_REQUEST['orderby'] ?? "title";
        $order   = $_REQUEST['order'] ?? "desc";

        $this->process_bulk_action();

        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        $this->items           = $this->GetItems($orderby, $order);
        $total                 = $this->GetTotalItemsCount();

        $this->set_pagination_args(array(
                                       'total_items' => $total,
                                       'per_page'    => $this->items_per_page,
                                       'total_pages' => ceil($total / $this->items_per_page)
                                   ));
    }

    public function process_bulk_action()
    {
        if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])) {
            $nonce  = filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING);
            $action = 'bulk-' . $this->_args['plural'];
            if ( !wp_verify_nonce($nonce, $action)) {
                wp_die('Security check failed!');
            }
        }

        $this->ProcessBuldActions();
    }

    abstract protected function ProcessBuldActions();

    public function get_columns(): array
    {
        return $this->GetColumns();
    }

    abstract protected function GetColumns(): array;

    public function get_sortable_columns(): array
    {
        return $this->GetSortableColumns();
    }

    abstract protected function GetSortableColumns(): array;

    abstract protected function GetItems($orderby, $order): array;

    abstract protected function GetTotalItemsCount(): int;

    public function get_bulk_actions(): array
    {
        return $this->GetBulkActions();
    }

    abstract protected function GetBulkActions(): array;

    public function column_cb($item): string
    {
        return '<input type="checkbox" name="wp_post_ids[]" value="' . $this->GetItemId($item) . '" />';
    }

    abstract protected function GetItemId($item): int;

    /**
     * @param object $item
     * @param string $column_name
     */
    public function column_default($item, $column_name)
    {
        echo $item->{$column_name};
    }

    public function views()
    {
        if ($this->GetTotalItemsCount() > 0) {
            parent::views();
        }
    }

    public function display()
    {
        if ($this->GetTotalItemsCount() > 0) {
            parent::display();
        } else {
            ?>
            <div class="tlbm-admin-empty-table">
                <span class="tlbm-text-big-light"><?php
                    echo $this->no_items_display ?></span>
            </div>
            <?php
        }
    }

    public function display_tablenav($which)
    {
        if ($this->GetTotalItemsCount() > 0) {
            parent::display_tablenav($which);
        }
    }

    protected function get_views(): array
    {
        $views            = array();
        $view_definitions = $this->GetViews();
        $current          = !empty($_REQUEST['filter']) ? $_REQUEST['filter'] : 'all';

        foreach ($view_definitions as $key => $title) {
            $class       = ($current == $key ? ' class="current"' : '');
            $all_url     = add_query_arg('filter', $key);
            $html        = $current == $key ? "<input type='hidden' name='filter' value='" . $key . "'>" : "";
            $html        .= "<a href='{$all_url}' {$class} >" . $title . "</a>";
            $views[$key] = $html;
        }

        return $views;
    }

    abstract protected function GetViews(): array;
}