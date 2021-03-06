<?php


namespace TLBM\Output;

if ( !defined('ABSPATH')) {
    return;
}

use TLBM\Admin\FormEditor\FormDataWalker;
use TLBM\Admin\FormEditor\RecursiveFormContentWalker;
use TLBM\Admin\Settings\Contracts\SettingsManagerInterface;
use TLBM\Admin\Settings\SingleSettings\BookingProcess\SinglePageBooking;
use TLBM\ApiUtils\Contracts\LocalizationInterface;
use TLBM\ApiUtils\Contracts\SanitizingInterface;
use TLBM\Entity\Form;
use TLBM\Output\Contracts\FormPrintInterface;
use TLBM\Repository\Contracts\EntityRepositoryInterface;


class FormPrint implements FormPrintInterface
{

    /**
     * @var EntityRepositoryInterface
     */
    private EntityRepositoryInterface $entityRepository;

    /**
     * @var LocalizationInterface
     */
    private LocalizationInterface $localization;

    /**
     * @var SettingsManagerInterface
     */
    private SettingsManagerInterface $settingsManager;

	/**
	 * @var SanitizingInterface
	 */
	private SanitizingInterface $sanitizing;

    public function __construct(SanitizingInterface $sanitizing, EntityRepositoryInterface $entityRepository, LocalizationInterface $localization, SettingsManagerInterface $settingsManager)
    {
        $this->entityRepository = $entityRepository;
        $this->localization     = $localization;
        $this->settingsManager  = $settingsManager;
		$this->sanitizing = $sanitizing;
    }

    /**
     * @param int $formId
     * @param mixed $inputVars
     *
     * @return string
     */
    public function printForm(int $formId, $inputVars = null): string
    {
        $form = $this->entityRepository->getEntity( Form::class, $formId);
        if($form != null) {
            $formWalker    = FormDataWalker::createFromData($form->getFormData());
            $contentWalker = new RecursiveFormContentWalker($inputVars);
            $result        = $this->sanitizing->ksesPostAndForm($formWalker->walkCallback($contentWalker));

            if ($form instanceof Form) {
                $html = "<form class='tlbm-frontend-form' action='" . $this->sanitizing->sanitizeUrl($_SERVER['REQUEST_URI']) . "' method='post'>";
                $html .= $result;
                $html .= "<input type='hidden' name='form' value='" . $form->getId() . "'>";

                if ($this->settingsManager->getValue(SinglePageBooking::class) == "on") {
                    $html .= "<input type='hidden' name='tlbm_action' value='dobooking'>";
                    $html .= wp_nonce_field("dobooking_action", "_wpnonce", true, false);
                    $html .= "<button class='tlbm-form-submit-button'>" . $this->localization->getText("Book now", TLBM_TEXT_DOMAIN) . "</button>";
                } else {
                    $html .= "<input type='hidden' name='tlbm_action' value='showbookingoverview'>";
                    $html .= wp_nonce_field("showbookingoverview_action", "_wpnonce", true, false);
                    $html .= "<button class='tlbm-form-submit-button'>" . $this->localization->getText("Continue", TLBM_TEXT_DOMAIN) . "</button>";
                }

                $html .= "</form>";
                return $html;
            }
        }

        return sprintf($this->localization->getText("<p><b>Unknown Form-Id: %s</b></p>", TLBM_TEXT_DOMAIN), $formId);
    }
}