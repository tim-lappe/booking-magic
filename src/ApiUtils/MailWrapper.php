<?php

namespace TLBM\ApiUtils;

class MailWrapper implements Contracts\MailInterface
{

    /**
     * @inheritDoc
     */
    public function sendMail(string $to, string $subject, string $message, array $headers = [], array $attachments = [])
    {
        wp_mail($to, $subject, $message, $headers, $attachments);
    }
}