<?php


namespace TLBM\Form;


use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use TLBM\Database\OrmManager;
use TLBM\Entity\Form;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

class FormManager {

    /**
     * @param $id
     *
     * @return false|Form
     */
	public static function GetForm($id) {
        try {
            $mgr = OrmManager::GetEntityManager();
            $form = $mgr->find("\TLBM\Entity\Form", $id);
            if ($form instanceof Form) {
                return $form;
            }
        } catch (Exception $e) {
            var_dump($e);
        }

        return null;
	}

    /**
     * @param Form $form
     * @throws Exception
     */
    public static function SaveForm( Form $form ) {
        $mgr = OrmManager::GetEntityManager();
        $mgr->persist($form);
        $mgr->flush();
    }

    /**
     * @param array $options
     * @param string $orderby
     * @param string $order
     * @param int $offset
     * @param int $limit
     * @return Form[]
     */
	public static function GetAllForms(array $options = array(), string $orderby = "title", string $order = "desc", int $offset = 0, int $limit = 0): array {
        $mgr = OrmManager::GetEntityManager();
        $qb = $mgr->createQueryBuilder();
        $qb ->select("f")
            ->from("\TLBM\Entity\Form", "f")
            ->orderBy("f." . $orderby, $order)
            ->setFirstResult($offset);
        if($limit > 0) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $result = $query->getResult();

        if(is_array($result)) {
            return $result;
        }

        return array();
	}

    /**
     * @param array $options
     * @return int
     * @throws Exception
     */
	public static function GetAllFormsCount(array $options = array()): int {
        $mgr = OrmManager::GetEntityManager();
        $qb = $mgr->createQueryBuilder();
        $qb ->select($qb->expr()->count("f"))
            ->from("\TLBM\Entity\Form", "f");

        $query = $qb->getQuery();
        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
	}
}