<?php


namespace TLBM\Admin\Tables;


use TLBM\Calendar\CalendarManager;
use TLBM\Model\Rule;
use TLBM\Rules\RulesManager;

if (!defined('ABSPATH')) {
    return;
}

if (!class_exists("\WP_Posts_List_Table")) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php');
}

class RulesListTable extends \WP_List_Table {

    public $specific_calendar_id = false;

    public function __construct($specific_calendar_id = false) {
        $this->specific_calendar_id = $specific_calendar_id;

        parent::__construct(array(
            "plural" => __("Rules", TLBM_TEXT_DOMAIN),
            "singular" => __("Rule", TLBM_TEXT_DOMAIN),
            "screen" => null
        ));
    }

    protected function display_tablenav( $which ) {
		//Important to remove the wp_nonce
    }

	public function get_columns(): array {
        return array(
            'title' => __('Title', TLBM_TEXT_DOMAIN),
            'calendars' => __('Calendars', TLBM_TEXT_DOMAIN),
            'priority' => __('Priority', TLBM_TEXT_DOMAIN),
        );
    }

    public function get_sortable_columns(): array {
        return array(
            'title' => array('title',true),
            'priority' => array('priority',true)
        );
    }


    public function prepare_items() {
        $orderby = isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : "priority";
        $order = isset($_REQUEST['order']) ? $_REQUEST['order'] : "desc";

        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());

        if(!$this->specific_calendar_id) {
            $this->items = RulesManager::GetAllRules(array(), $orderby, $order);
        } else {
            $this->items = RulesManager::GetAllRulesForCalendar($this->specific_calendar_id, array(), $orderby, $order);
        }

        $this->set_pagination_args(array(
            'total_items' => sizeof($this->items),
            'per_page' => 10
        ));
    }

    /**
     * @param Rule|object $item
     * @param string      $column_name
     */
    public function column_default($item, $column_name) {
        echo $item->{$column_name};
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
                if($key > 0) {
                    echo ", ";
                }
                echo $cal->title;
            }
        } else if($selection->selection_type == TLBM_CALENDAR_SELECTION_TYPE_ALL_BUT) {
            echo __("All but ", TLBM_TEXT_DOMAIN);
            foreach($selection->selected_calendar_ids as $key => $id) {
                $cal = CalendarManager::GetCalendar($id);
                if($key > 0) {
                    echo ", ";
                }
                echo "<s>" . $cal->title . "</s>";
            }
        }
    }

    /**
     * @param Rule|object $item
     */
    public function column_title(Rule $item) {
        $link = get_edit_post_link($item->wp_post_id);
        echo "<strong><a href='".$link."'>".$item->title."</a></strong>";
    }
}