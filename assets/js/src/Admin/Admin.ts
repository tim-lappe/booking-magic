import FormEditor from "./Formeditor/FormEditor";
import CalendarRulesEditing from "./CalendarRules/CalendarRulesEditing";
import FormFields from "./FormFields/FormFields";

export default class Admin {
    public static initAdmin() {
        this.attachFormEditor();
        this.attachCalendarRulesEditing();
        this.attachFormFields();
    }

    private static attachFormFields() {
        FormFields.attachFormFields();
    }

    private static attachCalendarRulesEditing() {
        CalendarRulesEditing.attachCalendarRulesEditing();
    }

    private static attachFormEditor() {
        let result = FormEditor.attachFormEditor();
        if(result == null) {
            console.log("No FormEditor attached");
        } else {
            console.log("FormEditor attached", result);
        }
    }
}