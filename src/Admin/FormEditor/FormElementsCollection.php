<?php

namespace TLBM\Admin\FormEditor;

if ( ! defined('ABSPATH')) {
    return;
}

use TLBM\Admin\FormEditor\Contracts\FormElementsCollectionInterface;
use TLBM\Admin\FormEditor\Elements\AddressElem;
use TLBM\Admin\FormEditor\Elements\CalendarElem;
use TLBM\Admin\FormEditor\Elements\CityElem;
use TLBM\Admin\FormEditor\Elements\ColumnsElem;
use TLBM\Admin\FormEditor\Elements\ContactEmailElem;
use TLBM\Admin\FormEditor\Elements\EmailElem;
use TLBM\Admin\FormEditor\Elements\FirstNameElem;
use TLBM\Admin\FormEditor\Elements\FormElem;
use TLBM\Admin\FormEditor\Elements\HrElem;
use TLBM\Admin\FormEditor\Elements\LastNameElem;
use TLBM\Admin\FormEditor\Elements\SelectElement;
use TLBM\Admin\FormEditor\Elements\SpacingElem;
use TLBM\Admin\FormEditor\Elements\TextBoxElem;
use TLBM\Admin\FormEditor\Elements\ZipElem;
use TLBM\Calendar\Contracts\CalendarGroupManagerInterface;
use TLBM\Calendar\Contracts\CalendarManagerInterface;

class FormElementsCollection implements FormElementsCollectionInterface
{

    /**
     * @var FormElem[]
     */
    private array $formElements = array();

    /**
     * @var CalendarGroupManagerInterface
     */
    private CalendarGroupManagerInterface $calendarGroupManager;

    /**
     * @var CalendarManagerInterface
     */
    private CalendarManagerInterface $calendarManager;

    public function __construct(
        CalendarManagerInterface $calendarManager,
        CalendarGroupManagerInterface $calendarGroupManager
    ) {
        $this->calendarManager = $calendarManager;
        $this->calendarGroupManager = $calendarGroupManager;

        $this->registerFormElements();
    }

    public function registerFormElements(): void
    {
        $this->addFormElement(new ColumnsElem("2er_columns", 2));
        $this->addFormElement(new ColumnsElem("3er_columns", 3));
        $this->addFormElement(new ColumnsElem("4er_columns", 4));
        $this->addFormElement(new ColumnsElem("5er_columns", 5));
        $this->addFormElement(new ColumnsElem("6er_columns", 6));
        $this->addFormElement(new HrElem());
        $this->addFormElement(new SpacingElem());

        $this->addFormElement(new CalendarElem($this->calendarManager, $this->calendarGroupManager));

        $this->addFormElement(new ContactEmailElem());
        $this->addFormElement(new FirstNameElem());
        $this->addFormElement(new LastNameElem());
        $this->addFormElement(new AddressElem());
        $this->addFormElement(new ZipElem());
        $this->addFormElement(new CityElem());

        $this->addFormElement(new EmailElem());
        $this->addFormElement(new TextBoxElem());
        $this->addFormElement(new SelectElement());
    }

    /**
     * @param FormElem $formelem
     */
    public function addFormElement(FormElem $formelem): void
    {
        $this->formElements[] = $formelem;
    }

    /**
     * @return FormElem[]
     */
    public function getRegisteredFormElements(): array
    {
        return $this->formElements;
    }

    public function getElemByUniqueName($unique_name): ?FormElem
    {
        foreach ($this->formElements as $elem) {
            if ($elem->unique_name == $unique_name) {
                return $elem;
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getCategorizedFormElements(): array
    {
        $formelements_arr = array();
        foreach ($this->formElements as $elem) {
            $formelements_arr[] = get_object_vars($elem);
        }

        return $formelements_arr;
    }
}