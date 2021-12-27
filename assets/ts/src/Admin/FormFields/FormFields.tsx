import {RuleActionsFields} from "./Fields/RuleActionsField/RuleActionsFields";
import * as React from "react";
import ReactDOM = require("react-dom");


export default class FormFields {

    public static attachFormFields() {
        document.querySelectorAll(".tlbm-rule-actions-field").forEach(( htmlelement: HTMLElement) => {
            ReactDOM.render(<RuleActionsFields dataset={htmlelement.dataset} />, htmlelement);
        });
    }
}