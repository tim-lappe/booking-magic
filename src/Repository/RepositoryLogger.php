<?php

namespace TLBM\Repository;

use Doctrine\DBAL\Logging\DebugStack;
use TLBM\Repository\Contracts\ORMInterface;

class RepositoryLogger
{
    /**
     * @var ORMInterface
     */
    private ORMInterface $repository;

    /**
     * @var DebugStack
     */
    private DebugStack $logger;

    /**
     * @param ORMInterface $repository
     */
    public function __construct(ORMInterface $repository)
    {
        $this->repository = $repository;
        $this->logger = new DebugStack();
    }

    public function start()
    {
        $this->repository->getEntityManager()->getConnection()->getConfiguration()->setSQLLogger($this->logger);
    }

    /**
     * @return array
     */
    public function end(): array
    {
        return $this->logger->queries;
    }
}