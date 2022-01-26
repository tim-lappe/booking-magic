<?php

namespace TLBM\Email\Contracts;

interface MailSenderInterface
{
    /**
     * @param $to
     * @param $email_option_name
     * @param array $vars
     *
     * @return bool|mixed|void
     */
    public function sendTemplate($to, $email_option_name, array $vars = array());
}