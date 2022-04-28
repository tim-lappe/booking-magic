<?php


namespace TLBM\Admin\Tables;

use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\ApiUtils\Contracts\SanitizingInterface;
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
    protected bool $slim = false;

    /**
     * @var int
     */
    protected int $itemsPerPage = 10;

    /**
     * @var ?array
     */
    protected ?array $fixedItems = null;

    /**
     * @var Column[]
     */
    protected array $columns = [];

    /**
     * @var LocalizationInterface
     */
    protected LocalizationInterface $localization;

	/**
	 * @var SanitizingInterface
	 */
	protected SanitizingInterface $sanitizing;

	/**
	 * @var EscapingInterface
	 */
	protected EscapingInterface $escaping;

	/**
	 * @param $titlePlural
	 * @param $titleSingular
	 * @param int $itemsPerPage
	 */
    public function __construct($titlePlural, $titleSingular, int $itemsPerPage = 10)
    {
        $this->localization = MainFactory::get(LocalizationInterface::class);
		$this->sanitizing = MainFactory::get(SanitizingInterface::class);
		$this->escaping = MainFactory::get(EscapingInterface::class);

        parent::__construct(array(
                                "plural" => $titlePlural,
                                "singular" => $titleSingular,
                                "screen" => null
                            )
        );


        $this->itemsPerPage   = $itemsPerPage;
        $this->columns = $this->getColumns();
    }


    /**
     * @return bool
     */
    public function isSlim(): bool
    {
        return $this->slim;
    }

    /**
     * @param bool $slim
     */
    public function setSlim(bool $slim): void
    {
        $this->slim = $slim;
        $this->columns = $this->getColumns();
    }

    /**
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * @param int $itemsPerPage
     */
    public function setItemsPerPage(int $itemsPerPage): void
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @return ?array
     */
    public function getFixedItems(): ?array
    {
        return $this->fixedItems;
    }

    /**
     * @param ?array $fixedItems
     */
    public function setFixedItems(?array $fixedItems): void
    {
        $this->fixedItems = $fixedItems;
    }

    /**
     * @param callable $getIdCallback
     *
     * @return Column
     */
    protected function getCheckboxColumn(callable $getIdCallback): Column {
		$escaping = MainFactory::get(EscapingInterface::class);

        return new Column("cb", "<input type='checkbox' />", false, function ($item) use ($getIdCallback, $escaping) {
            echo '<input type="checkbox" name="ids[]" value="' . $escaping->escAttr(call_user_func($getIdCallback, $item)) . '" />';
        });
    }

    /**
     *
     * @SuppressWarnings(PHPMD)
     * @return void
     */
    public function prepare_items()
    {
        $orderby = $this->sanitizing->sanitizeKey($_REQUEST['orderby'] ?? "id");
        $order   = $this->sanitizing->sanitizeKey($_REQUEST['order'] ?? "desc");
        $page    = $this->sanitizing->sanitizeKey($_REQUEST['paged'] ?? 1);
        $page    = intval($page);

        if(!in_array($order, ["asc", "desc"])) {
            $order = "";
        }

        $this->process_bulk_action();

        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        $this->items           = $this->getFinalItems($orderby, $order, $page);
        $total                 = $this->getTotalItemsCount();

        $this->set_pagination_args(array(
                                       'total_items' => $total,
                                       'per_page' => $this->itemsPerPage,
                                       'total_pages' => ceil($total / $this->itemsPerPage)
                                   ));
    }

    /**
     * @param string $orderby
     * @param string $order
     * @param int $page
     *
     * @return array
     */
    protected function getFinalItems(string $orderby, string $order, int $page = 1): array
    {
        if ($this->fixedItems !== null) {
            usort($this->fixedItems, function ($a, $b) use ($orderby, $order) {
                if (isset($a->{$orderby}) && isset($b->{$orderby})) {
                    if ($order == "ASC") {
                        return is_numeric($a) && is_numeric($b) ? (intval($a) > intval($b) ? 1 : -1) : strcmp($a->{$orderby}, $b->{$orderby});
                    } elseif ($orderby == "DESC") {
                        return is_numeric($a) && is_numeric($b) ? (intval($a) < intval($b) ? 1 : -1) : strcmp($b->{$orderby}, $a->{$orderby});
                    }
                }

                return 0;
            });

            return array_splice($this->fixedItems, ($page - 1) * $this->itemsPerPage, $this->itemsPerPage);
        } else {
            return $this->getItems($orderby, $order, $page);
        }
    }


    /**
     * @SuppressWarnings(PHPMD)
     * @return void
     */
    public function process_bulk_action()
    {
		$nonce = $this->sanitizing->sanitizeKey($_POST['_wpnonce'] ?? "");
		$action = $this->sanitizing->sanitizeKey($_REQUEST['action'] ?? "");
		$ids = [];

		if(isset($_REQUEST['ids']) && is_array($_REQUEST['ids'])) {
			foreach ( $_REQUEST['ids'] as $id ) {
				$ids[] = $this->sanitizing->sanitizeKey( $id );
			}
		}

        if (!empty($nonce)) {
            $nonce  = filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING);
            $action = 'bulk-' . $this->_args['plural'];
            if ( !wp_verify_nonce($nonce, $action)) {
                wp_die('Security check failed!');
            }
        }

        if(!empty($action) && count($ids) > 0) {
			$this->processBuldActions($action, $ids);
        }
    }

    abstract protected function processBuldActions(string $action, array $ids);

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
        return $this->sanitizing->sanitizeKey($_REQUEST['filter'] ?? "");
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
        $current          = !empty($_REQUEST['filter']) ? $this->sanitizing->sanitizeKey($_REQUEST['filter']) : 'all';

        foreach ($view_definitions as $key => $title) {
            $class       = ($current == $key ? ' class="current"' : '');
            $all_url     = $this->escaping->escUrl(add_query_arg('filter', $key));
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