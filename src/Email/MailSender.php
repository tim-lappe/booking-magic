<?php


namespace TLBM\Email;


use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Email\Contracts\MailSenderInterface;

class MailSender implements MailSenderInterface
{

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

    public function __construct(SettingsManagerInterface $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    /**
     * @param string $to
     * @param string $email_option_name
     * @param array $vars
     *
     * @return mixed
     */
    public function sendTemplate(string $to, string $email_option_name, array $vars = array())
    {
        $opt = $this->settingsManager->getValue($email_option_name);
        if ($opt) {
            $subject = $opt['subject'];
            $content = $opt['message'];
            $message = '<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>' . $subject . '</title><meta name="viewport" content="width=device-width, initial-scale=1.0"/></head><body>';

            $inline_css = file_get_contents(TLBM_DIR . "/assets/css/mail/main.css");
            $message    .= sprintf("<style>%s</style>", $inline_css);
            $message    .= $content;

            $message .= "</body></html>";
            $vars    = $vars + self::getDefaultTemplateVars();
            foreach ($vars as $key => $value) {
                $message = str_replace("{{" . $key . "}}", $value, $message);
            }

            return wp_mail($to, $subject, $message);
        }

        return false;
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