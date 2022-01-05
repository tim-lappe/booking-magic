import CalendarRulesEditing from "./CalendarRules/CalendarRulesEditing";
import FormFields from "./FormFields/FormFields";
import {BookingStatesSettingEditor} from "./BookingStatesSetting/BookingStatesSettingEditor";

export default class Admin {
    public static initAdmin() {
        this.attachCalendarRulesEditing();
        this.attachFormFields();

        BookingStatesSettingEditor.init();
    }

    private static attachFormFields() {
        FormFields.attachFormFields();
    }

    private static attachCalendarRulesEditing() {
        CalendarRulesEditing.attachCalendarRulesEditing();
    }
}