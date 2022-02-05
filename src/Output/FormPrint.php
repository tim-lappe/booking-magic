<?php


namespace TLBM\Output;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Admin\FormEditor\FormDataWalker;
use TLBM\Admin\FormEditor\RecursiveFormContentWalker;
use TLBM\Entity\Form;
use TLBM\Output\Contracts\FormPrintInterface;
use TLBM\Output\Contracts\FrontendMessengerInterface;
use TLBM\Repository\Contracts\FormRepositoryInterface;

class FormPrint implements FormPrintInterface
{

    /**
     * @var FormRepositoryInterface
     */
    private FormRepositoryInterface $formManager;

    /**
     * @var FrontendMessengerInterface
     */
    private FrontendMessengerInterface $frontendMessenger;

    public function __construct(FormRepositoryInterface $formManager, FrontendMessengerInterface $frontendMessenger)
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
        if($form != null) {
            $html = $this->frontendMessenger->getContent();

            $formWalker    = FormDataWalker::createFromData($form->getFormData());
            $contentWalker = new RecursiveFormContentWalker($inputVars);
            $result        = $formWalker->walkCallback($contentWalker);

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
        }

        return sprintf(__("<p><b>Unknown Form-Id: %s</b></p>", TLBM_TEXT_DOMAIN), $formId);
    }
}