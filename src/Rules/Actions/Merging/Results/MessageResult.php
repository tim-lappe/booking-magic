<?php

namespace TLBM\Rules\Actions\Merging\Results;

use TLBM\Rules\Actions\Merging\Contracts\MergeResultInterface;

class MessageResult implements MergeResultInterface
{

    /**
     * @var string[]
     */
    public array $messages = array();

    /**
     * @param string $message
     *
     * @return void
     */
    public function addMessage(string $message)
    {
        $this->messages[] = $message;
    }

    /**
     * @param MergeResultInterface ...$mergeResults
     *
     */
    public function sumResults(MergeResultInterface ...$mergeResults)
    {
        foreach ($mergeResults as $result) {
            if($result instanceof MessageResult) {
                $this->setMessages(array_diff($result->getMessages(), $this->getMessages()));
            }
        }
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param string[] $messages
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }
}