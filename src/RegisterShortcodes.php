<?php


namespace TLBM;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Output\Contracts\FormPrintInterface;
use TLBM\Repository\Contracts\BookingRepositoryInterface;
use TLBM\Request\Contracts\RequestManagerInterface;

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

    public function __construct(FormPrintInterface $formPrint, RequestManagerInterface $requestManager)
    {
        add_action("init", array($this, "addShortcodes"));

        $this->formPrint = $formPrint;
        $this->requestManager = $requestManager;
    }

    /**
     * @return void
     */
    public function addShortcodes()
    {
        add_shortcode(TLBM_SHORTCODETAG_FORM, array($this, "formShortcode"));
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
                    return $this->formPrint->printForm($args['id'], $this->requestManager->getVars());
                }
            }
        }

        return "";
    }
}