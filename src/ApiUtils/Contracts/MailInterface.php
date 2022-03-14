<?php

namespace TLBM\ApiUtils\Contracts;

interface MailInterface
{
    /**
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param array $headers
     * @param array $attachments
     *
     * @return mixed
     */
    public function sendMail(string $to, string $subject, string $message, array $headers = [], array $attachments = []);
}