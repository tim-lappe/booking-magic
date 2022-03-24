<?php

namespace TLBM\Rules\Actions\Merging\Merger;

use TLBM\Rules\Actions\ActionData\MessageData;
use TLBM\Rules\Actions\Merging\Contracts\MergeResultInterface;
use TLBM\Rules\Actions\Merging\Results\MessageResult;

class MessageMerger extends Merger
{

    public function merge(?MergeResultInterface $mergeResult = null): ?MergeResultInterface
    {
        if ($mergeResult == null) {
            $mergeResult = MainFactory::create(TimeCapacitiesCollectionResults::class);
        }

        if ($mergeResult instanceof MessageResult) {
            $mergeResult->addMessage($this->getActionData()->getMessage());
            if ($this->getNextMerging()) {
                return $this->getNextMerging()->merge($mergeResult);
            }
        }

        return $mergeResult;
    }

    /**
     * @return MessageData|null
     */
    protected function getActionData(): ?MessageData
    {
        $actionData = parent::getActionData();
        if($actionData instanceof MessageData) {
            return $actionData;
        }

        return null;
    }

    /**
     * @return MessageMerger|null
     */
    protected function getNextMerging(): ?MessageMerger
    {
        $next = parent::getNextMerging();
        if($next instanceof MessageMerger) {
            return $next;
        }

        return null;
    }
}