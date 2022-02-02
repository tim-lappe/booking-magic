<?php


namespace TLBM\Output;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Admin\FormEditor\FormDataWalker;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\Admin\FormEditor\RecursiveFormContentWalker;
use TLBM\Entity\Form;
use TLBM\Form\Contracts\FormManagerInterface;
use TLBM\Output\Contracts\FormPrintInterface;
use TLBM\Output\Contracts\FrontendMessengerInterface;

class FormPrint implements FormPrintInterface
{

    /**
     * @var FormManagerInterface
     */
    private FormManagerInterface $formManager;

    /**
     * @var FrontendMessengerInterface
     */
    private FrontendMessengerInterface $frontendMessenger;

    public function __construct(FormManagerInterface $formManager, FrontendMessengerInterface $frontendMessenger)
    {
        $this->formManager = $formManager;
        $this->frontendMessenger = $frontendMessenger;
    }

    /**
     * @param int $formId
     * @param mixed $inputVars
     *
     * @return string
     */
    public function printForm(int $formId, $inputVars = null): string
    {
        $form = $this->formManager->getForm($formId);
        $html = $this->frontendMessenger->getContent();

        $formWalker = FormDataWalker::createFromData($form->getFormData());
        $contentWalker = new RecursiveFormContentWalker($inputVars);
        $result = $formWalker->walkCallback($contentWalker);

        if ($form instanceof Form) {
            $html .= "<form class='tlbm-frontend-form' action='" . $_SERVER['REQUEST_URI'] . "' method='post'>";
            $html .= $result;
            $html .= "<input type='hidden' name='form' value='" . $form->getId() . "'>";

            if (get_option("single_page_booking") == "on") {
                $html .= "<input type='hidden' name='tlbm_action' value='dobooking'>";
                $html .= wp_nonce_field("dobooking_action", "_wpnonce", true, false);
                $html .= "<button class='tlbm-form-submit-button'>" . __("Book now", TLBM_TEXT_DOMAIN) . "</button>";
            } else {
                $html .= "<input type='hidden' name='tlbm_action' value='showbookingoverview'>";
                $html .= wp_nonce_field("showbookingoverview_action", "_wpnonce", true, false);
                $html .= "<button class='tlbm-form-submit-button'>" . __("Continue", TLBM_TEXT_DOMAIN) . "</button>";
            }

            $html .= "</form>";

            return $html;
        }

        return "";
    }
}