<?php

namespace TLBM\Admin\RuleActionsEditor\Contracts;

use TLBM\Admin\RuleActionsEditor\Actions\RuleActionEditorElem;

interface RuleActionsEditorCollectionInterface
{
    /**
     * @template T
     *
     * @param class-string<T> $actionEditorElem
     *
     * @return void
     */
    public function registerRuleActionEditorElem(string $actionEditorElem);

    /**
     * @return RuleActionEditorElem[]
     */
    public function getRegisteredRuleActions(): array;
}