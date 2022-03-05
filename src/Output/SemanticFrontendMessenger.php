<?php

namespace TLBM\Output;

use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\ApiUtils\Contracts\LocalizationInterface;

class SemanticFrontendMessenger
{
    /**
     * @var FrontendMessenger
     */
    private FrontendMessenger $frontendMessenger;

    /**
     * @var LocalizationInterface
     */
    private LocalizationInterface $localization;

    /**
     * @param FrontendMessenger $frontendMessenger
     * @param LocalizationInterface $localization
     */
    public function __construct(FrontendMessenger $frontendMessenger, LocalizationInterface $localization)
    {
        $this->frontendMessenger = $frontendMessenger;
        $this->localization =   $localization;
    }

    /**
     * @param string $html
     *
     * @return void
     */
    public function addMessage(string $html)
    {
        $this->frontendMessenger->addMessage($html);
    }

    /**
     * @param LinkedFormData[] $invalidFields
     *
     * @return void
     */
    public function addMissingRequiredFieldsMessage(array $invalidFields)
    {
        $errors = [];
        foreach($invalidFields as $field) {
            $title = $field->getLinkedSettings()->getValue("title");
            if(!empty($title)) {
                $errors[] = $title;
            }
        }

        $this->frontendMessenger->addMessage($this->localization->__("Not all required fields were filled out: <br>" . implode("<br>", $errors), TLBM_TEXT_DOMAIN));

    }
}