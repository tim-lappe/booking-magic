import FormFields from "./FormFields/FormFields";
import {BookingStatesSettingEditor} from "./BookingStatesSetting/BookingStatesSettingEditor";
import {Charts} from "./Charts/Charts";

export default class Admin {
    public static initAdmin() {
        BookingStatesSettingEditor.init();
        Charts.attachLineCharts();
        FormFields.attachFormFields();

    }
}