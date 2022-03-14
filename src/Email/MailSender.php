<?php


namespace TLBM\Email;

use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\Emails\EmailSetting;
use TLBM\Admin\Settings\SingleSettings\Emails\SenderMail;
use TLBM\Admin\Settings\SingleSettings\Emails\SenderName;
use TLBM\ApiUtils\Contracts\MailInterface;
use TLBM\Email\Contracts\MailSenderInterface;

class MailSender implements MailSenderInterface
{

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    /**
     * @var MailInterface
     */
    private MailInterface $mail;

    public function __construct(SettingsManagerInterface $settingsManager, MailInterface $mail)
    {
        $this->mail            = $mail;
        $this->settingsManager = $settingsManager;
    }

    /**
     *
     * @param string $to
     * @param class-string<EmailSetting> $emailSetting
     * @param EmailSemantic|null $emailSemantic
     *
     * @return mixed
     */
    public function sendTemplate(string $to, string $emailSetting, ?EmailSemantic $emailSemantic = null)
    {
        $setting = $this->settingsManager->getSetting($emailSetting);
        if ($setting instanceof EmailSetting) {
            $opt = $setting->getValue();
            if ($setting->isEnabled()) {
                if ($opt) {
                    $subject = $opt['subject'];
                    $content = $opt['message'];
                    $message = '<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>' . $subject . '</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/></head><body>';

                    $inline_css = file_get_contents(TLBM_DIR . "/assets/css/mail/main.css");
                    $message    .= sprintf("<style>%s</style>", $inline_css);
                    $message    .= $content;

                    $message .= "</body></html>";
                    $message = $this->replaceTemplateVars($message, $emailSemantic);
                    $headers = $this->getDefaultHeader();

                    return $this->mail->sendMail($to, $subject, $message, $headers);
                }
            }
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function getDefaultHeader(): array
    {
        $fromName = $this->settingsManager->getValue(SenderName::class);
        $fromMail = $this->settingsManager->getValue(SenderMail::class);

        return ["From: " . $fromName . "<" . $fromMail . ">",
            "Content-type: text/html; charset=utf-8"
        ];
    }

    /**
     * @param string $message
     * @param EmailSemantic|null $emailSemantic
     *
     * @return string
     */
    private function replaceTemplateVars(string $message, ?EmailSemantic $emailSemantic = null): string
    {
        $vars = $this->getDefaultTemplateVars();

        if ($emailSemantic != null) {
            $vars = $vars + $emailSemantic->getValues();
        }

        foreach ($vars as $key => $value) {
            $message = str_replace("{{" . $key . "}}", $value, $message);
        }

        return $message;
    }

    /**
     * @return array
     */
    private function getDefaultTemplateVars(): array
    {
        return array(
            "site_name" => get_bloginfo("name")
        );
    }
}