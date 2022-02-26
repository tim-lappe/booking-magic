<?php


namespace TLBM\Admin\Tables;

use TLBM\CMS\Contracts\LocalizationInterface;
use TLBM\MainFactory;
use WP_List_Table;

/**
 * @template T
 *
 */
abstract class TableBase extends WP_List_Table
{

    /**
     * @var bool
     */
    public bool $slim = false;

    /**
     * @var int
     */
    public int $itemsPerPage = 10;

    /**
     * @var string
     */
    public string $noItemsDisplay = "";

    /**
     * @var Column[]
     */
    protected array $columns = [];

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

    public function __construct($titlePlural, $titleSingular, $itemsPerPage = 10, $noItemsDisplay = "")
    {
        $this->localization = MainFactory::get(LocalizationInterface::class);

        parent::__construct(array(
                                "plural" => $titlePlural,
                                "singular" => $titleSingular,
                                "screen" => null
                            )
        );

        if (empty($noItemsDisplay)) {
            $noItemsDisplay = $this->localization->__("Nothing to show", TLBM_TEXT_DOMAIN);
        }

        $this->noItemsDisplay = $noItemsDisplay;
        $this->itemsPerPage   = $itemsPerPage;

        $this->columns = $this->getColumns();
    }

    /**
     * @param callable $getIdCallback
     *
     * @return Column
     */
    protected function getCheckboxColumn(callable $getIdCallback): Column {
        return new Column("cb", "<input type='checkbox' />", false, function ($item) use ($getIdCallback) {
            echo '<input type="checkbox" name="ids[]" value="' . call_user_func($getIdCallback, $item) . '" />';
        });
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
        $page    = $_REQUEST['paged'] ?? 1;
        $page    = intval($page);

        if(!in_array($order, ["asc", "desc"])) {
            $order = "";
        }

        $this->process_bulk_action();

        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        $this->items           = $this->getItems($orderby, $order, $page);
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

        if(isset($_REQUEST['action'])) {
            $action = $_REQUEST['action'];
            if ($action) {
                $this->processBuldActions($action);
            }
        }
    }

    abstract protected function processBuldActions(string $action);

    /**
     * @SuppressWarnings(PHPMD)
     * @return array
     */
    final public function get_columns(): array
    {
        $columns = [];
        foreach ($this->columns as $column) {
            $columns[$column->getName()] = $column->getTitle();
        }

        return $columns;
    }


    /**
     * @param mixed $which
     * @SuppressWarnings(PHPMD)
     * @return void
     */
    final public function display_tablenav($which)
    {
        if ( !$this->slim && $this->getTotalItemsCount() > 0) {
            parent::display_tablenav($which);
        }
    }

    public function getCurrentView(): string
    {
        return $_REQUEST['filter'] ?? "";
    }

    /**
     * @param mixed $which
     *
     * @return void
     */
    final public function extra_tablenav($which): void
    {
        $this->tableNav((string)$which);
    }

    /**
     * @param string $which
     *
     * @return void
     */
    abstract protected function tableNav(string $which): void;

    /**
     * @return Column[]
     */
    abstract protected function getColumns(): array;

    /**
     * @param mixed $item
     *
     * @return string|null
     */
    protected function getRowClickUrl($item): ?string
    {
        return null;
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @return array
     */
    final public function get_sortable_columns(): array
    {
        $sortableArr = [];
        foreach ($this->columns as $column) {
            if($column->isSortable()) {
                $sortableArr[$column->getName()] = [$column->getName(), true];
            }
        }

        return $sortableArr;
    }

    /**
     * @param string $orderby
     * @param string $order
     * @param int $page
     *
     * @return array
     */
    abstract protected function getItems(string $orderby, string $order, int $page): array;

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
     * @SuppressWarnings(PHPMD)
     * @param T $item
     * @param string $column_name
     */
    public function column_default($item, $column_name)
    {
        foreach ($this->columns as $column) {
            if($column->getName() == $column_name) {
                call_user_func($column->getDisplay(), $item);
            }
        }
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param T $item
     */
    public function column_cb($item)
    {
        foreach ($this->columns as $column) {
            if($column->getName() == "cb") {
                call_user_func($column->getDisplay(), $item);
            }
        }
    }


    /**
     * @return void
     */
    public function views()
    {
        parent::views();
    }

    /**
     * @return void
     */
    public function display()
    {
        parent::display();
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