<?php

namespace TLBM\Booking;

use Exception;
use TLBM\Admin\FormEditor\Elements\FormElem;
use TLBM\Admin\FormEditor\Elements\FormInputElem;
use TLBM\Admin\FormEditor\FormDataWalker;
use TLBM\Admin\FormEditor\LinkedFormData;
use TLBM\Entity\Form;
use TLBM\Form\Contracts\FormManagerInterface;
use TLBM\MainFactory;

class BookingProcessor
{
    /**
     * @var FormManagerInterface
     */
    private FormManagerInterface $formManager;

    /**
     * @var mixed
     */
    private $vars;

    /**
     * @var Form
     */
    private Form $form;


    public function __construct(FormManagerInterface $formManager)
    {
        $this->formManager = $formManager;
    }

    /**
     * @return LinkedFormData[]
     */
    public function validate(): array
    {
        $formData = $this->form->getFormData();
        $formWalker = FormDataWalker::createFromData($formData);

        $invalidFields = [];
        foreach($formWalker->walkLinkedElements($this->getVars()) as $linkedFormData) {
            if(!$linkedFormData->validateInput()) {
                $invalidFields[] = $linkedFormData;
            }
        }

        return $invalidFields;
    }

    /**
     * @param mixed $vars
     *
     * @return void
     */
    public function setVars($vars)
    {
        if(isset($vars['form'])) {
            $form = $this->formManager->getForm($vars['form']);
            if($form) {
                $this->form = $form;
            }
        }

        $this->vars = $vars;
    }

    /**
     * @return mixed
     */
    public function getVars()
    {
        return $this->vars;
    }


    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * @param mixed $vars
     *
     * @return ?BookingProcessor
     */
    public static function createFromVars($vars): ?BookingProcessor
    {
        try {
            $bookingProcessor = MainFactory::create(BookingProcessor::class);
            $bookingProcessor->setVars($vars);
            return $bookingProcessor;

        } catch (Exception $exception) {
            if(WP_DEBUG) {
                var_dump($exception);
            }
        }

        return null;
    }
}