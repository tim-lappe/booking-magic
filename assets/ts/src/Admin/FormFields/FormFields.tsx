import * as React from "react";
import ReactDOM = require("react-dom");
import {CalendarSelect} from "./CalendarSelect";
import {RuleActionsFields} from "./RuleActionsField/RuleActionsFields";
import {PeriodSelect} from "./PeriodSelect";
import {Editor} from "./FormEditor/Editor";
import {DateRangeSelect} from "./DateRangeSelect";
import {DateTime} from "../../Core/Adapter/DateTime";
import {Utils} from "../../Utils";


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

        document.querySelectorAll(".tlbm-date-range-field").forEach(( htmlelement: HTMLElement) => {
            try {
                let fromDateTime = DateTime.fromObj(JSON.parse(Utils.decodeUriComponent(htmlelement.dataset.from)));
                try {
                    let toDateTime = DateTime.fromObj(JSON.parse(Utils.decodeUriComponent(htmlelement.dataset.to)));
                    ReactDOM.render(<DateRangeSelect formName={htmlelement.dataset.name} fromDateTime={fromDateTime} toDateTime={toDateTime}/>, htmlelement);
                } catch {
                    ReactDOM.render(<DateRangeSelect formName={htmlelement.dataset.name} fromDateTime={fromDateTime} toDateTime={null}/>, htmlelement);
                }

            } catch {

            }
        });
    }
}