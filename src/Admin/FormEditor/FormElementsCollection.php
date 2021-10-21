<?php


namespace TLBM\Admin\FormEditor;

if( ! defined( 'ABSPATH' ) ) {
    return;
}


use TLBM\Admin\FormEditor\FormElements\AddressElem;
use TLBM\Admin\FormEditor\FormElements\CalendarElem;
use TLBM\Admin\FormEditor\FormElements\CityElem;
use TLBM\Admin\FormEditor\FormElements\ColumnsElem;
use TLBM\Admin\FormEditor\FormElements\ContactEmailElem;
use TLBM\Admin\FormEditor\FormElements\EmailElem;
use TLBM\Admin\FormEditor\FormElements\FirstNameElem;
use TLBM\Admin\FormEditor\FormElements\FormElem;
use TLBM\Admin\FormEditor\FormElements\HrElem;
use TLBM\Admin\FormEditor\FormElements\LastNameElem;
use TLBM\Admin\FormEditor\FormElements\SpacingElem;
use TLBM\Admin\FormEditor\FormElements\TextBoxElem;
use TLBM\Admin\FormEditor\FormElements\ZipElem;

class FormElementsCollection {

    /**
     * @var FormElem[]
     */
    public static $formelements = array();

    public static function RegisterFormElements() {

        self::AddFormElement(new ColumnsElem("2er_columns", 2));
        self::AddFormElement(new ColumnsElem("3er_columns", 3));
        self::AddFormElement(new ColumnsElem("4er_columns", 4));
        self::AddFormElement(new ColumnsElem("5er_columns", 5));
        self::AddFormElement(new ColumnsElem("6er_columns", 6));
	    self::AddFormElement(new HrElem());
	    self::AddFormElement(new SpacingElem());

        self::AddFormElement(new CalendarElem());

        self::AddFormElement(new ContactEmailElem());
        self::AddFormElement(new FirstNameElem());
        self::AddFormElement(new LastNameElem());
        self::AddFormElement(new AddressElem());
        self::AddFormElement(new ZipElem());
        self::AddFormElement(new CityElem());

	    self::AddFormElement(new EmailElem());
        self::AddFormElement(new TextBoxElem());
    }

    /**
     * @return FormElem[]
     */
    public static function GetRegisteredFormElements(): array {
        return self::$formelements;
    }

    public static function GetElemByUniqueName($unique_name) {
        foreach (self::$formelements as $elem) {
            if ($elem->unique_name == $unique_name) {
                return $elem;
            }
        }

        return false;
    }

    /**
     * @param $formelem
     */
    public static function AddFormElement($formelem) {
        self::$formelements[] = $formelem;
    }
}