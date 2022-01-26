<?php

namespace TLBM\Form\Contracts;

use Exception;
use TLBM\Entity\Form;

interface FormManagerInterface
{

    /**
     * @param $id
     *
     * @return Form
     */
    public function getForm($id): ?Form;

    /**
     * @param Form $form
     *
     * @throws Exception
     */
    public function saveForm(Form $form): void;

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
    ): array;

    /**
     * @param array $options
     *
     * @return int
     * @throws Exception
     */
    public function getAllFormsCount(array $options = array()): int;
}