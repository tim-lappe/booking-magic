import * as React from "react";
import ReactDOM = require("react-dom");
import {CalendarSelect} from "./CalendarSelect";
import {RuleActionsFields} from "./RuleActionsField/RuleActionsFields";
import {PeriodSelect} from "./PeriodSelect";
import {Editor} from "./FormEditor/Editor";


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

        document.querySelectorAll(".tlbm-form-editor-field").forEach(( htmlelement: HTMLElement) => {
            ReactDOM.render(<Editor dataset={htmlelement.dataset} />, htmlelement);
        });
    }
}