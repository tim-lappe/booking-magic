import FormFieldCalendarSelector from "./Fields/FormFieldCalendarSelector";
import FormFieldPeriodsSelector from "./Fields/FormFieldPeriodsSelector";
import {FormFieldRuleActionFields} from "./Fields/FormFieldRuleActionFields";

export default class FormFields {

    public static attachFormFields() {
        FormFieldCalendarSelector.attach();
        FormFieldPeriodsSelector.attach();
        FormFieldRuleActionFields.attach();
    }
}