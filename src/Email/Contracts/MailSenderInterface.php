<?php

namespace TLBM\Email\Contracts;

use TLBM\Email\EmailSemantic;

interface MailSenderInterface
{
    /**
     * @param string $to
     * @param string $emailSetting
     * @param EmailSemantic|null $emailSemantic
     *
     * @return mixed
     */
    public function sendTemplate(string $to, string $emailSetting, ?EmailSemantic $emailSemantic = null);
}