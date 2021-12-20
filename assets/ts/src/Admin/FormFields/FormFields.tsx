import FormFieldCalendarSelector from "./Fields/FormFieldCalendarSelector";
import FormFieldPeriodsSelector from "./Fields/FormFieldPeriodsSelector";
import {RuleActionsFields} from "./Fields/RuleActionsField/RuleActionsFields";
import * as React from "react";
import ReactDOM = require("react-dom");


export default class FormFields {

    public static attachFormFields() {
        FormFieldCalendarSelector.attach();
        FormFieldPeriodsSelector.attach();

        const ffs = document.querySelectorAll(".tlbm-rule-actions-field") as NodeListOf<HTMLElement>;
        ffs.forEach(( htmlelement) => {
            ReactDOM.render(<RuleActionsFields data-json={htmlelement.dataset.json} />, htmlelement);
        });
    }
}