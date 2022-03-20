<?php

namespace TLBM\Admin\RuleActionsEditor;

use TLBM\Admin\RuleActionsEditor\Actions\RuleActionEditorElem;
use TLBM\Admin\RuleActionsEditor\Contracts\RuleActionsEditorCollectionInterface;
use TLBM\MainFactory;

class RuleActionsEditorCollection implements RuleActionsEditorCollectionInterface
{
    /**
     * @var class-string[]
     */
    private array $ruleActionEditorElems = [];

    /**
     * @template T
     *
     * @param class-string<T> $actionEditorElem
     *
     * @return void
     */
    public function registerRuleActionEditorElem(string $actionEditorElem)
    {
        if ( !in_array($actionEditorElem, $this->ruleActionEditorElems)) {
            $this->ruleActionEditorElems[] = $actionEditorElem;
        }
    }

    /**
     * @return RuleActionEditorElem[]
     */
    public function getRegisteredRuleActions(): array
    {
        $elems = [];
        foreach ($this->ruleActionEditorElems as $elem) {
            $ruleActionEditorElem = MainFactory::create($elem);
            if ($ruleActionEditorElem instanceof RuleActionEditorElem) {
                $elems[] = $ruleActionEditorElem;
            }
        }

        return $elems;
    }
}