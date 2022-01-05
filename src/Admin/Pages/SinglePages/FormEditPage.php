<?php

namespace TLBM\Admin\Pages\SinglePages;

class FormEditPage extends FormPageBase {

    public function __construct( ) {
        parent::__construct( __("Form", TLBM_TEXT_DOMAIN), "booking-magic-form" );

        $this->parent_slug = "booking-magic";
    }

    public function ShowFormPageContent() {

    }

    public function OnSave($vars): array {

    }
}