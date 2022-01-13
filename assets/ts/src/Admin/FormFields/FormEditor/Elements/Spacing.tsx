import {BasicEditorElement} from "./BasicEditorElement";
import {FormElement} from "../../../Entity/FormEditor/FormElement";
import * as React from "react";

export class Spacing extends BasicEditorElement<FormElement> {

    render() {
        let height = this.state.formNode.formData.spacing ?? 100;

        return (
            <div style={{height: height + "px"}} className={'tlbm-form-item-spacing-box'}>

            </div>
        )
    }
}