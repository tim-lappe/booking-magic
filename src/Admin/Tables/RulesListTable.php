<?php


namespace TLBM\Admin\Tables;


use TLBM\Calendar\Contracts\CalendarManagerInterface;
use TLBM\Entity\Rule;
use TLBM\Rules\Contracts\RulesManagerInterface;
use WP_List_Table;

if ( !defined('ABSPATH')) {
    return;
}

if ( !class_exists("\WP_Posts_List_Table")) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php');
}

class RulesListTable extends WP_List_Table
{

    /**
     * @var bool|mixed
     */
    public $specificCalendarId = false;

    /**
     * @var RulesManagerInterface
     */
    private RulesManagerInterface $rulesManager;

    /**
     * @var CalendarManagerInterface
     */
    private CalendarManagerInterface $calendarManager;

    public function __construct(
        RulesManagerInterface $rulesManager,
        CalendarManagerInterface $calendarManager,
        $specificCalendarId = false
    ) {
        $this->specificCalendarId = $specificCalendarId;
        $this->rulesManager       = $rulesManager;
        $this->calendarManager      = $calendarManager;

        parent::__construct(array(
                                "plural"   => __("Rules", TLBM_TEXT_DOMAIN),
                                "singular" => __("Rule", TLBM_TEXT_DOMAIN),
                                "screen"   => null
                            ));
    }

    /**
     * @SuppressWarnings(PHPMD)
     *
     * @return void
     */
    public function prepare_items()
    {
        $orderby = $_REQUEST['orderby'] ?? "priority";
        $order   = $_REQUEST['order'] ?? "desc";

        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());

        if ( !$this->specificCalendarId) {
            $this->items = $this->rulesManager->getAllRules(array(), $orderby, $order);
        } else {
            $this->items = $this->rulesManager->getAllRulesForCalendar(
                $this->specificCalendarId, array(), $orderby, $order
            );
        }

        $this->set_pagination_args(array(
                                       'total_items' => sizeof($this->items),
                                       'per_page'    => 10
                                   ));
    }

    /**
     * @SuppressWarnings(PHPMD)
     *
     * @return array
     */
    public function get_columns(): array
    {
        return array(
            'title'     => __('Title', TLBM_TEXT_DOMAIN),
            'calendars' => __('Calendars', TLBM_TEXT_DOMAIN),
            'priority'  => __('Priority', TLBM_TEXT_DOMAIN),
        );
    }

    /**
     * @SuppressWarnings(PHPMD)
     *
     * @return array[]
     */
    public function get_sortable_columns(): array
    {
        return array(
            'title'    => array('title', true),
            'priority' => array('priority', true)
        );
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param Rule|object $item
     * @param string $column_name
     */
    public function column_default($item, $column_name)
    {
        echo $item->{$column_name};
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param Rule $item
     */
    public function column_calendars(Rule $item)
    {
        $selection = $item->getCalendarSelection();
        if ($selection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL) {
            echo __("All", TLBM_TEXT_DOMAIN);
        } elseif ($selection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ONLY) {
            foreach ($selection->getCalendarIds() as $key => $id) {
                $cal = $this->calendarManager->getCalendar($id);
                if ($key > 0) {
                    echo ", ";
                }
                echo $cal->getTitle();
            }
        } elseif ($selection->getSelectionMode() == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
            echo __("All but ", TLBM_TEXT_DOMAIN);
            foreach ($selection->getCalendarIds() as $key => $id) {
                $cal = $this->calendarManager->getCalendar($id);
                if ($key > 0) {
                    echo ", ";
                }
                echo "<s>" . $cal->getTitle() . "</s>";
            }
        }
    }

    /**
     * @param Rule $item
     * @SuppressWarnings(PHPMD)
     */
    public function column_title(Rule $item)
    {
        $link = get_edit_post_link($item->getId());
        echo "<strong><a href='" . $link . "'>" . $item->title . "</a></strong>";
    }

    /**
     * @param mixed $which
     * @SuppressWarnings(PHPMD)
     * @return void
     */
    protected function display_tablenav($which)
    {

    }
}