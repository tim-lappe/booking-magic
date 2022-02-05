<?php

namespace TLBM\Email\Contracts;

interface MailSenderInterface
{
    /**
     * @param string $to
     * @param string $emailSetting
     * @param array $vars
     *
     * @return mixed
     */
    public function sendTemplate(string $to, string $emailSetting, array $vars = array());
}