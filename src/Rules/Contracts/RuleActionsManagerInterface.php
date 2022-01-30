<?php

namespace TLBM\Rules\Contracts;


use TLBM\Entity\RuleAction;
use TLBM\Rules\Actions\Contracts\ActionHandlerInterface;

interface RuleActionsManagerInterface
{

    /**
     * @param string $term
     * @param string $class
     *
     * @return bool
     */
    public function registerActionHandlerClass(string $term, string $class): bool;

    /**
     * @return array
     */
    public function getAllActionsHandlerClasses(): array;

    /**
     *
     * @param RuleAction $action
     *
     * @return ?ActionHandlerInterface
     */
    public function getActionHandler(RuleAction $action): ?ActionHandlerInterface;
}