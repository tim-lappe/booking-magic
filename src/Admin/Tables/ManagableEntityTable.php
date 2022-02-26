<?php

namespace TLBM\Admin\Tables;

use TLBM\Entity\ManageableEntity;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\EntityRepositoryInterface;
use TLBM\Repository\Query\ManageableEntityQuery;


abstract class ManagableEntityTable extends TableBase
{
    /**
     * @var class-string<ManageableEntity>
     */
    private string $entityClass;

    /**
     * @var EntityRepositoryInterface
     */
    protected EntityRepositoryInterface $entityRepository;

    /**
     * @param class-string<ManageableEntity> $entityClass
     * @param string $titlePlural
     * @param string $titleSingular
     * @param int $itemsPerPage
     * @param string $noItemsDisplay
     */
    public function __construct(string $entityClass, string $titlePlural, string $titleSingular, int $itemsPerPage = 10, string $noItemsDisplay = "")
    {
        $this->entityClass = $entityClass;
        $this->entityRepository = MainFactory::get(EntityRepositoryInterface::class);
        parent::__construct($titlePlural, $titleSingular, $itemsPerPage, $noItemsDisplay);
    }

    /**
     * @inheritDoc
     */
    protected function getColumns(): array
    {
        return array(
            $this->getCheckboxColumn(function ($item) {
                return $item->getId();
            }),

            new Column("date_edited", $this->localization->__('Date last edited', TLBM_TEXT_DOMAIN), true, function ($item) {
                /**
                 * @var ManageableEntity $item
                 */
                echo $item->getDateTimeEdited();
            }),

            new Column("date_created", $this->localization->__('Date created', TLBM_TEXT_DOMAIN), true, function ($item) {
                /**
                 * @var ManageableEntity $item
                 */
                echo $item->getDateTimeCreated();
            }),
        );
    }

    protected function getQueryObject(): ManageableEntityQuery
    {
        return MainFactory::create(ManageableEntityQuery::class);
    }

    /**
     * @param string|null $orderby
     * @param string|null $order
     * @param int|null $page
     *
     * @return ManageableEntityQuery
     */
    protected function getQuery(?string $orderby, ?string $order, ?int $page): ManageableEntityQuery
    {
        $query = $this->getQueryObject();
        $query->setEntityClass($this->entityClass);

        if($orderby == "date_edited") {
            $query->setOrderBy([[TLBM_ENTITY_QUERY_ALIAS . ".timestampEdited", $order]]);

        } elseif($orderby == "date_created") {
            $query->setOrderBy([[TLBM_ENTITY_QUERY_ALIAS . ".timestampCreated", $order]]);

        } else {
            $query->setOrderBy([[TLBM_ENTITY_QUERY_ALIAS . ".timestampEdited", "desc"]]);
        }

        if($page != null) {
            if ($this->getTotalItemsCount() > $this->itemsPerPage) {
                $query->setOffset($this->itemsPerPage * ($page - 1));
                $query->setLimit($this->itemsPerPage);
            }
        }

        return $query;
    }

    /**
     * @inheritDoc
     */
    protected function getItems(string $orderby, string $order, int $page): array
    {
        $query = $this->getQuery($orderby,$order, $page);
        return iterator_to_array($query->getResult());
    }

    /**
     * @inheritDoc
     */
    protected function getTotalItemsCount(): int
    {
        $query = $this->getQuery(null,null, null);
        return $query->getResultCount();
    }

    protected function processBuldActions(string $action)
    {
        if(isset($_REQUEST['ids'])) {
            $ids = $_REQUEST['ids'];
            if (is_array($ids)) {
                if ($action == "delete") {
                    foreach ($ids as $id) {
                        $entity = $this->entityRepository->getEntity($this->entityClass, $id);
                        if ($entity) {
                            $this->entityRepository->deleteEntityPermanently($entity);
                        }
                    }
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    protected function getBulkActions(): array
    {
        return array(
            'delete' => $this->localization->__('Delete', TLBM_TEXT_DOMAIN)
        );
    }

    /**
     * @inheritDoc
     */
    protected function getViews(): array
    {
        return array();
    }

    protected function tableNav(string $which): void
    {
        // TODO: Implement tableNav() method.
    }
}