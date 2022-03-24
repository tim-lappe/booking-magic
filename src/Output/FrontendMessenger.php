<?php


namespace TLBM\Output;


use TLBM\Output\Contracts\FrontendMessengerInterface;

if ( !defined('ABSPATH')) {
    return;
}

class FrontendMessenger implements FrontendMessengerInterface
{

    /**
     * @var array
     */
    private array $frontendMsgs = [];

    /**
     * @param string $html
     *
     */
    public function addMessage(string $html)
    {
        $this->frontendMsgs[] = $html;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        if (count($this->frontendMsgs) > 0) {
            $html = '<div class="tlbm-messages">';
            foreach ($this->frontendMsgs as $msg) {
                $html .= "$msg<br>";
            }
            $html .= "</div>";

            return $html;
        }

        return '';
    }
}