<?php

namespace TLBM\Email\Contracts;

interface MailSenderInterface
{
    /**
     * @param string $to
     * @param string $email_option_name
     * @param array $vars
     *
     * @return mixed
     */
    public function sendTemplate(string $to, string $email_option_name, array $vars = array());
}