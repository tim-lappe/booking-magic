<?php


namespace TLBM;

if ( ! defined('ABSPATH')) {
    return;
}


use TLBM\Output\Contracts\FormPrintInterface;

class RegisterShortcodes
{

    private FormPrintInterface $formPrint;


    public function __construct(FormPrintInterface $formPrint)
    {
        add_action("init", array($this, "addShortcodes"));
        $this->formPrint = $formPrint;
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
        $request = $GLOBALS['TLBM_REQUEST'];
        if (sizeof($args) > 0) {
            if (isset($args['id'])) {
                if ($request instanceof Request && $request->currentAction != null && $request->currentAction->html_output) {
                    return $request->currentAction->getDisplayContent($_REQUEST);
                } else {
                    return $this->formPrint->printForm($args['id']);
                }
            }
        }

        return "";
    }
}