import FormFields from "./FormFields/FormFields";
import {BookingStatesSettingEditor} from "./BookingStatesSetting/BookingStatesSettingEditor";

export default class Admin {
    public static initAdmin() {
        this.attachFormFields();
        BookingStatesSettingEditor.init();
    }

    private static attachFormFields() {
        FormFields.attachFormFields();
    }
}