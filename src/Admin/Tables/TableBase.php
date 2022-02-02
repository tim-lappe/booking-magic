<?php


namespace TLBM\Admin\Tables;

use WP_List_Table;

abstract class TableBase extends WP_List_Table
{

    /**
     * @var int
     */
    public int $itemsPerPage = 10;

    /**
     * @var string
     */
    public string $noItemsDisplay = "";

    public function __construct($titlePlural, $titleSingular, $itemsPerPage = 10, $noItemsDisplay = "")
    {
        parent::__construct(array(
                                "plural" => $titlePlural,
                                "singular" => $titleSingular,
                                "screen" => null
                            )
        );

        if (empty($noItemsDisplay)) {
            $noItemsDisplay = __("Nothing to show", TLBM_TEXT_DOMAIN);
        }

        $this->noItemsDisplay = $noItemsDisplay;
        $this->itemsPerPage   = $itemsPerPage;
    }

    /**
     *
     * @SuppressWarnings(PHPMD)
     * @return void
     */
    public function prepare_items()
    {
        $orderby = $_REQUEST['orderby'] ?? "id";
        $order   = $_REQUEST['order'] ?? "desc";

        $this->process_bulk_action();

        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        $this->items           = $this->getItems($orderby, $order);
        $total                 = $this->getTotalItemsCount();

        $this->set_pagination_args(array(
                                       'total_items' => $total,
                                       'per_page' => $this->itemsPerPage,
                                       'total_pages' => ceil($total / $this->itemsPerPage)
                                   ));
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @return void
     */
    public function process_bulk_action()
    {
        if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])) {
            $nonce  = filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING);
            $action = 'bulk-' . $this->_args['plural'];
            if ( !wp_verify_nonce($nonce, $action)) {
                wp_die('Security check failed!');
            }
        }

        $this->processBuldActions();
    }

    abstract protected function processBuldActions();

    /**
     * @SuppressWarnings(PHPMD)
     * @return array
     */
    public function get_columns(): array
    {
        return $this->getColumns();
    }

    /**
     * @return array
     */
    abstract protected function getColumns(): array;

    /**
     * @SuppressWarnings(PHPMD)
     * @return array
     */
    public function get_sortable_columns(): array
    {
        return $this->getSortableColumns();
    }

    /**
     * @return array
     */
    abstract protected function getSortableColumns(): array;

    /**
     * @param string $orderby
     * @param string $order
     *
     * @return array
     */
    abstract protected function getItems(string $orderby, string $order): array;

    /**
     * @return int
     */
    abstract protected function getTotalItemsCount(): int;

    /**
     * @SuppressWarnings(PHPMD)
     * @return array
     */
    public function get_bulk_actions(): array
    {
        return $this->getBulkActions();
    }

    /**
     * @return array
     */
    abstract protected function getBulkActions(): array;

    /**
     * @param mixed $item
     * @SuppressWarnings(PHPMD)
     * @return string
     */
    public function column_cb($item): string
    {
        return '<input type="checkbox" name="wp_post_ids[]" value="' . $this->getItemId($item) . '" />';
    }

    /**
     * @param mixed $item
     *
     * @return int
     */
    abstract protected function getItemId($item): int;

    /**
     * @SuppressWarnings(PHPMD)
     * @param object $item
     * @param string $column_name
     */
    public function column_default($item, $column_name)
    {
        echo $item->{$column_name};
    }

    /**
     * @return void
     */
    public function views()
    {
        if ($this->getTotalItemsCount() > 0) {
            parent::views();
        }
    }

    /**
     * @return void
     */
    public function display()
    {
        if ($this->getTotalItemsCount() > 0) {
            parent::display();
        } else {
            ?>
            <div class="tlbm-admin-empty-table">
                <span class="tlbm-text-big-light"><?php
                    echo $this->noItemsDisplay ?></span>
            </div>
            <?php
        }
    }

    /**
     * @param mixed $which
     * @SuppressWarnings(PHPMD)
     * @return void
     */
    public function display_tablenav($which)
    {
        if ($this->getTotalItemsCount() > 0) {
            parent::display_tablenav($which);
        }
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @return array
     */
    protected function get_views(): array
    {
        $views            = array();
        $view_definitions = $this->getViews();
        $current          = !empty($_REQUEST['filter']) ? $_REQUEST['filter'] : 'all';

        foreach ($view_definitions as $key => $title) {
            $class       = ($current == $key ? ' class="current"' : '');
            $all_url     = add_query_arg('filter', $key);
            $html        = $current == $key ? "<input type='hidden' name='filter' value='" . $key . "'>" : "";
            $html        .= "<a href='$all_url' $class >" . $title . "</a>";
            $views[$key] = $html;
        }

        return $views;
    }

    /**
     * @return array
     */
    abstract protected function getViews(): array;
}