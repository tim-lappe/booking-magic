<?php

namespace TLBM\Repository\Query;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Iterator;
use TLBM\ApiUtils\Contracts\EscapingInterface;
use TLBM\MainFactory;
use TLBM\Repository\Contracts\ORMInterface;

abstract class BaseQuery
{

    /**
     * @var array|null
     */
    private ?array $orderBy = null;

    /**
     * @var int|null
     */
    private ?int $offset = null;

    /**
     * @var int|null
     */
    private ?int $limit = null;

    /**
     * @var ORMInterface
     */
    protected ORMInterface $repository;

	/**
	 * @var EscapingInterface
	 */
	protected EscapingInterface $escaping;

    /**
     * @var array
     */
    private array $additionalWhereExpressions = [];

    public function __construct(ORMInterface $repository)
    {
        $this->repository = $repository;
		$this->escaping = MainFactory::get(EscapingInterface::class);
    }

    /**
     * @return Iterator
     */
    public function getResult(): Iterator
    {
        foreach ($this->getQuery()->getResult() as $resultObj) {
            yield $resultObj;
        }
    }

    public function getQuery(): ?Query
    {
        try {
            $queryBuilder = $this->createQueryBuilder();
            $this->buildQuery($queryBuilder);
            if(count($this->additionalWhereExpressions) > 0) {
                $queryBuilder->andWhere(implode(" AND " , $this->additionalWhereExpressions));
            }

            return $queryBuilder->getQuery();
        } catch (Exception $exception) {
            if(WP_DEBUG) {
                echo $this->escaping->escHtml($exception->getMessage());
            }
        }

        return null;
    }

    public function addWhere(string $expr)
    {
        $this->additionalWhereExpressions[] = $expr;
    }

    /**
     * @return int
     */
    public function getResultCount(): int
    {
        try {
            $queryBuilder = $this->createQueryBuilder();

            $this->buildQuery($queryBuilder, true);
            if(count($this->additionalWhereExpressions) > 0) {
                $queryBuilder->andWhere(implode(" AND " , $this->additionalWhereExpressions));
            }

            return intval($queryBuilder->getQuery()->getSingleScalarResult());
        } catch (Exception $exception) {
            if(WP_DEBUG) {
                echo $this->escaping->escHtml($exception->getMessage()) . " " . $this->escaping->escHtml($exception->getTraceAsString());
            }
        }

        return 0;
    }

    /**
     * @return array|null
     */
    public function getOrderBy(): ?array
    {
        return $this->orderBy;
    }

    /**
     * @param array|null $orderBy
     */
    public function setOrderBy(?array $orderBy): void
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     */
    public function setOffset(?int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     */
    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return ?array
     */
    public function getDefaultOrderBy(): ?array
    {
        return null;
    }

    public function getExpr(): Query\Expr
    {
        return $this->repository->getEntityManager()->getExpressionBuilder();
    }

    /**
     * @return QueryBuilder
     */
    protected function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->repository->getEntityManager()->createQueryBuilder();

        $orderByArr = $this->orderBy ?? $this->getDefaultOrderBy();
        if($orderByArr != null) {
            foreach ($orderByArr as $orderBy) {
                if(count($orderBy)) {
                    $queryBuilder->addOrderBy($orderBy[0], $orderBy[1] ?? "ASC");
                }
            }
        }

        if($this->offset != null) {
            $queryBuilder->setFirstResult($this->offset);
        }

        if($this->limit != null) {
            $queryBuilder->setMaxResults($this->limit);
        }

        return $queryBuilder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param bool $onlyCount
     *
     * @return void
     */
    abstract protected function buildQuery(QueryBuilder $queryBuilder, bool $onlyCount = false): void;
}