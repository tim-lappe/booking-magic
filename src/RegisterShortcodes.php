<?php


namespace TLBM;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\CMS\Contracts\HooksInterface;
use TLBM\CMS\Contracts\ShortcodeInterface;
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

    /**
     * @var ShortcodeInterface
     */
    private ShortcodeInterface $shortcode;

    public function __construct(FormPrintInterface $formPrint, RequestManagerInterface $requestManager, HooksInterface $hooks, ShortcodeInterface $shortcode)
    {
        $this->formPrint = $formPrint;
        $this->shortcode = $shortcode;
        $this->requestManager = $requestManager;

        $hooks->addAction("init", array($this, "addShortcodes"));
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
                    return $this->formPrint->printForm($args['id'], $this->requestManager->getVars());
                }
            }
        }

        return "";
    }
}