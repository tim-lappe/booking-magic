<?php

namespace TLBM\Admin\FormEditor;

if( ! defined( 'ABSPATH' ) ) {
    return;
}

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
use TLBM\Admin\FormEditor\Elements\SpacingElem;
use TLBM\Admin\FormEditor\Elements\TextBoxElem;
use TLBM\Admin\FormEditor\Elements\ZipElem;

class ElementsCollection {

    /**
     * @var FormElem[]
     */
    public static array $formelements = array();

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
     * @return array
     */
    public static function GetCategorizedFormElements(): array {
        $formelements_arr = array();
        foreach(self::$formelements as $elem) {
            $formelements_arr[] = get_object_vars($elem);
        }
        return $formelements_arr;
    }

    /**
     * @param $formelem
     */
    public static function AddFormElement($formelem) {
        self::$formelements[] = $formelem;
    }
}