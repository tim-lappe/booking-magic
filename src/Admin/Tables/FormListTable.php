<?php


namespace TLBM\Admin\Tables;


use Exception;
use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\FormEditPage;
use TLBM\Entity\Form;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Repository\Query\FormQuery;

class FormListTable extends TableBase
{

    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

    public function __construct(EntityRepositoryInterface $entityRepository, AdminPageManagerInterface $adminPageManager)
    {
        $this->entityRepository      = $entityRepository;
        $this->adminPageManager = $adminPageManager;

        parent::__construct(
            __("Forms", TLBM_TEXT_DOMAIN), __("Form", TLBM_TEXT_DOMAIN), 10, __("You haven't created any forms yet", TLBM_TEXT_DOMAIN)
        );
    }

    protected function processBuldActions()
    {
        //TODO: Bulk Actions für Form Tabelle implementieren
    }

    /**
     * @param string $orderby
     * @param string $order
     * @param int $page
     *
     * @return array
     * @SuppressWarnings(PHPMD)
     */
    protected function getItems(string $orderby, string $order, int $page): array
    {
        $formQuery = MainFactory::create(FormQuery::class);

        if($orderby == "title") {
            $formQuery->setOrderBy([[TLBM_FORM_QUERY_ALIAS . ".title", $order]]);
        }

        return iterator_to_array($formQuery->getResult());
    }

    /**
     * @return array
     */
    protected function getViews(): array
    {
        return array(
            "all"     => __("All", TLBM_TEXT_DOMAIN),
            "trashed" => __("Trash", TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            $this->getCheckboxColumn(function ($item) {
                return $item->getId();
            }),

            new Column("title", __("Title", TLBM_TEXT_DOMAIN), true, function ($item) {
                $page = $this->adminPageManager->getPage(FormEditPage::class);
                if ($page instanceof FormEditPage) {
                    $link = $page->getEditLink($item->getId());
                    if ( !empty($item->getTitle())) {
                        echo "<strong><a href='" . $link . "'>" . $item->getTitle() . "</a></strong>";
                    } else {
                        echo "<strong><a href='" . $link . "'>" . $item->getId() . "</a></strong>";
                    }
                }
            })
        ];
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @return array
     */
    protected function getBulkActions(): array
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
     * @return int
     * @throws Exception
     */

    protected function getTotalItemsCount(): int
    {
        return $this->entityRepository->getEntityCount(Form::class);
    }

    /**
     * @param string $which
     *
     * @return void
     */
    protected function tableNav(string $which): void
    {
        // TODO: Implement tableNav() method.
    }
}