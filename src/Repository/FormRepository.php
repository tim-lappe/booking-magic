<?php


namespace TLBM\Repository;


use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use TLBM\Entity\Form;
use TLBM\Repository\Contracts\FormRepositoryInterface;
use TLBM\Repository\Contracts\ORMInterface;

if ( !defined('ABSPATH')) {
    return;
}

class FormRepository implements FormRepositoryInterface
{


    /**
     * @var ORMInterface
     */
    private ORMInterface $repository;

    public function __construct(ORMInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $id
     *
     * @return Form
     */
    public function getForm($id): ?Form
    {
        try {
            $mgr  = $this->repository->getEntityManager();
            $form = $mgr->find("\TLBM\Entity\Form", $id);
            if ($form instanceof Form) {
                return $form;
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        return null;
    }

    /**
     * @param Form $form
     *
     * @throws Exception
     */
    public function saveForm(Form $form): void
    {
        $mgr = $this->repository->getEntityManager();
        $mgr->persist($form);
        $mgr->flush();
    }

    /**
     * @param array $options
     * @param string $orderby
     * @param string $order
     * @param int $offset
     * @param int $limit
     *
     * @return Form[]
     */
    public function getAllForms(
        array $options = array(),
        string $orderby = "title",
        string $order = "desc",
        int $offset = 0,
        int $limit = 0
    ): array {
        $mgr = $this->repository->getEntityManager();
        $qb  = $mgr->createQueryBuilder();
        $qb->select("f")->from("\TLBM\Entity\Form", "f")->orderBy("f." . $orderby, $order)->setFirstResult($offset);
        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }

        $query  = $qb->getQuery();
        $result = $query->getResult();

        if (is_array($result)) {
            return $result;
        }

        return array();
    }

    /**
     * @param array $options
     *
     * @return int
     * @throws Exception
     */
    public function getAllFormsCount(array $options = array()): int
    {
        $mgr = $this->repository->getEntityManager();
        $qb  = $mgr->createQueryBuilder();
        $qb->select($qb->expr()->count("f"))->from("\TLBM\Entity\Form", "f");

        $query = $qb->getQuery();
        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            return 0;
        }
    }
}