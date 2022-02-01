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
     * @return string[]
     */
    public function getMergeResult(): array
    {
        return $this->messages;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function addMessage(string $message)
    {
        $this->messages[] = $message;
    }
}