<?php


namespace TLBM\Output;

if ( ! defined('ABSPATH')) {
    return;
}

use TLBM\Entity\Form;
use TLBM\Form\Contracts\FormManagerInterface;
use TLBM\Output\Contracts\FormPrintInterface;

class FormPrint implements FormPrintInterface
{

    private FormManagerInterface $formManager;

    public function __construct(FormManagerInterface $formManager)
    {
        $this->formManager = $formManager;
    }

    /**
     * @param $id
     *
     * @return string
     */
    public function printForm($id): string
    {
        $form = $this->formManager->getForm($id);
        $html = FrontendMessenger::GetMessangesPrint();

        if ($form instanceof Form) {
            $html .= "<form action='" . $_SERVER['REQUEST_URI'] . "' method='post'>";
            $html .= $form->GetFrontendHtml();
            $html .= "<input type='hidden' name='form' value='" . $form->GetId() . "'>";

            if (get_option("single_page_booking") == "on") {
                $html .= "<input type='hidden' name='action' value='dobooking'>";
                $html .= wp_nonce_field("dobooking_action", "_wpnonce", true, false);
                $html .= "<button class='tlbm-form-submit-button'>" . __("Book now", TLBM_TEXT_DOMAIN) . "</button>";
            } else {
                $html .= "<input type='hidden' name='action' value='showbookingoverview'>";
                $html .= wp_nonce_field("showbookingoverview_action", "_wpnonce", true, false);
                $html .= "<button class='tlbm-form-submit-button'>" . __("Continue", TLBM_TEXT_DOMAIN) . "</button>";
            }

            $html .= "</form>";

            return $html;
        }

        return "";
    }
}