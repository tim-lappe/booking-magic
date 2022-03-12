<?php


namespace TLBM\Admin\Tables;

use TLBM\Admin\Pages\Contracts\AdminPageManagerInterface;
use TLBM\Admin\Pages\SinglePages\FormEditPage;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\Entity\Form;
use TLBM\Repository\Query\ManageableEntityQuery;

class FormListTable extends ManagableEntityTable
{

    /**
     * @var AdminPageManagerInterface
     */
    private AdminPageManagerInterface $adminPageManager;

    public function __construct(AdminPageManagerInterface $adminPageManager, LocalizationInterface $localization)
    {
        $this->adminPageManager = $adminPageManager;
        parent::__construct(
            Form::class, $localization->__("Forms", TLBM_TEXT_DOMAIN), $localization->__("Form", TLBM_TEXT_DOMAIN), 10, $localization->__("You haven't created any forms yet", TLBM_TEXT_DOMAIN)
        );
    }


    protected function getQuery(?string $orderby, ?string $order, ?int $page, bool $useCustomFilters = true): ManageableEntityQuery
    {
        $query = parent::getQuery($orderby, $order, $page);
        if ($orderby == "title") {
            $query->setOrderBy([[TLBM_ENTITY_QUERY_ALIAS . ".title", $order]]);
        }

        return $query;
    }

    /**
     * @return array
     */
    protected function getColumns(): array
    {
        $columns = parent::getColumns();
        array_splice($columns, 1, 0, [
            new Column("title", $this->localization->__("Title", TLBM_TEXT_DOMAIN), true, function ($item) {
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
        ]);

        return $columns;
    }
}