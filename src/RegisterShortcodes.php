<?php


namespace TLBM;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\ApiUtils\Contracts\HooksInterface;
use TLBM\ApiUtils\Contracts\ShortcodeInterface;
use TLBM\Output\Contracts\FormPrintInterface;
use TLBM\Repository\Contracts\BookingRepositoryInterface;
use TLBM\Request\Contracts\RequestManagerInterface;
use TLBM\Session\SessionManager;

class RegisterShortcodes
{

    /**
     * @var FormPrintInterface
     */
    private FormPrintInterface $formPrint;

    /**
     * @var RequestManagerInterface
     */
    private RequestManagerInterface $requestManager;

    /**
     * @var ShortcodeInterface
     */
    private ShortcodeInterface $shortcode;

    private SessionManager $sessionManager;

    public function __construct(FormPrintInterface $formPrint, RequestManagerInterface $requestManager, HooksInterface $hooks, ShortcodeInterface $shortcode, SessionManager $sessionManager)
    {
        $this->formPrint      = $formPrint;
        $this->shortcode      = $shortcode;
        $this->requestManager = $requestManager;
        $this->sessionManager = $sessionManager;

        $hooks->addAction("init", [$this, "addShortcodes"]);
    }

    /**
     * @return void
     */
    public function addShortcodes()
    {
        $this->shortcode->addShortcode(TLBM_SHORTCODETAG_FORM, array($this, "formShortcode"));
    }

    /**
     * @param array $args
     *
     * @return string
     */
    public function formShortcode(array $args): string
    {
        $bookingManager = MainFactory::get(BookingRepositoryInterface::class);
        $bookingManager->cleanExpiredReservedBookings();

        if (sizeof($args) > 0) {
            if (isset($args['id'])) {
                if ($this->requestManager->hasContent()) {
                    return $this->requestManager->getContent();
                } else {
                    $vars                = $this->requestManager->getVars();
                    $lastFormFieldValues = $this->sessionManager->getValue("lastFormFieldValues");
                    if (is_array($lastFormFieldValues)) {
                        foreach ($lastFormFieldValues as $key => $value) {
                            $vars[$key] = $value;
                        }
                    }

                    return $this->formPrint->printForm($args['id'], $vars);
                }
            }
        }

        return "";
    }
}