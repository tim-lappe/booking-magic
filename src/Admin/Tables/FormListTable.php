<?php


namespace TLBM\Admin\Tables;


use Exception;
use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\FormEditPage;
use TLBM\Entity\Form;
use TLBM\Form\Contracts\FormManagerInterface;

class FormListTable extends TableBase
{

    /**
     * @var FormManagerInterface
     */
    private FormManagerInterface $formManager;

    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

    public function __construct(FormManagerInterface $formManager, AdminPageManagerInterface $adminPageManager)
    {
        $this->formManager      = $formManager;
        $this->adminPageManager = $adminPageManager;

        parent::__construct(
            __("Forms", TLBM_TEXT_DOMAIN), __("Form", TLBM_TEXT_DOMAIN), 10, __("You haven't created any forms yet", TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @param Form $item
     */
    public function column_title(Form $item)
    {
        $page = $this->adminPageManager->getPage(FormEditPage::class);
        if ($page instanceof FormEditPage) {
            $link = $page->getEditLink($item->getId());
            if ( !empty($item->getTitle())) {
                echo "<strong><a href='" . $link . "'>" . $item->getTitle() . "</a></strong>";
            } else {
                echo "<strong><a href='" . $link . "'>" . $item->getId() . "</a></strong>";
            }
        }
    }

    protected function ProcessBuldActions()
    {
        if (isset($_REQUEST['wp_post_ids'])) {
            $ids    = $_REQUEST['wp_post_ids'];
            $action = $this->current_action();
            foreach ($ids as $id) {
                if ($action == "delete") {
                    wp_update_post(array(
                                       "ID"          => $id,
                                       "post_status" => "trash"
                                   ));
                } elseif ($action == "delete_permanently") {
                    wp_delete_post($id);
                } elseif ($action == "restore") {
                    wp_update_post(array(
                                       "ID"          => $id,
                                       "post_status" => "publish"
                                   ));
                }
            }
        }
    }

    protected function GetItems($orderby, $order): array
    {
        $pt_args = array();
        if (isset($_REQUEST['filter']) && $_REQUEST['filter'] == "trashed") {
            $pt_args = array("post_status" => "trash");
        }

        return $this->formManager->getAllForms($pt_args, $orderby, $order);
    }

    protected function GetViews(): array
    {
        return array(
            "all"     => __("All", TLBM_TEXT_DOMAIN),
            "trashed" => __("Trash", TLBM_TEXT_DOMAIN)
        );
    }

    protected function GetColumns(): array
    {
        return array(
            "cb"    => "<input type='checkbox' />",
            "title" => __('Title', TLBM_TEXT_DOMAIN)
        );
    }

    protected function GetSortableColumns(): array
    {
        return array(
            'title' => array('title', true)
        );
    }

    protected function GetBulkActions(): array
    {
        if (isset($_REQUEST['filter']) && $_REQUEST['filter'] == "trashed") {
            return array(
                'delete_permanently' => __('Delete permanently', TLBM_TEXT_DOMAIN),
                'restore'            => __('Restore', TLBM_TEXT_DOMAIN)
            );
        } else {
            return array(
                'delete' => __('Move to trash', TLBM_TEXT_DOMAIN)
            );
        }
    }

    /**
     * @param Form $item
     *
     * @return int
     */
    protected function GetItemId($item): int
    {
        return $item->getId();
    }

    /**
     * @return int
     * @throws Exception
     */

    protected function GetTotalItemsCount(): int
    {
        return $this->formManager->getAllFormsCount();
    }
}