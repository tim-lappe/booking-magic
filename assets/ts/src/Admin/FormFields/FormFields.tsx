import * as React from "react";
import ReactDOM = require("react-dom");
import {CalendarSelect} from "./Fields/CalendarSelect";
import {RuleActionsFields} from "./Fields/RuleActionsField/RuleActionsFields";
import {PeriodSelect} from "./Fields/PeriodSelect";


export default class FormFields {

    public static attachFormFields() {
        document.querySelectorAll(".tlbm-period-select-field").forEach(( htmlelement: HTMLElement) => {
            ReactDOM.render(<PeriodSelect dataset={htmlelement.dataset} />, htmlelement);
        });

        document.querySelectorAll(".tlbm-rule-actions-field").forEach(( htmlelement: HTMLElement) => {
            ReactDOM.render(<RuleActionsFields dataset={htmlelement.dataset} />, htmlelement);
        });

        document.querySelectorAll(".tlbm-calendar-picker").forEach(( htmlelement: HTMLElement) => {
            ReactDOM.render(<CalendarSelect dataset={htmlelement.dataset} />, htmlelement);
        });
    }
}