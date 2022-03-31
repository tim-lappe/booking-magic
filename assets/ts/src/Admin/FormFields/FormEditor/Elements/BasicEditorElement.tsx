import * as React from "react";
import {FormElement} from "../../../Entity/FormEditor/FormElement";
import {FormEditorNode} from "../../../Entity/FormEditor/FormEditorNode";
import {Editor} from "../Editor";
import {Localization} from "../../../../Localization";

interface BasicEditorElementProps {
    formEditor: Editor;
    formNode: FormEditorNode;
}

interface BasicEditorElementState<T extends FormElement> {
    formNode: FormEditorNode;
    formElement: T;
}

export class BasicEditorElement<T extends FormElement> extends React.Component<BasicEditorElementProps, BasicEditorElementState<T>> {

    constructor(props) {
        super(props);
        this.state = {
            formNode: this.props.formNode,
            formElement: this.props.formEditor.formElementsManager.getFormElementByName<T>(this.props.formNode.formData.uniqueName)
        }
    }

    render() {
        return (
            <div className={'tlbm-form-item-box'}>
                <span className={'tlbm-form-settings-print-title'}>
                    {this.state.formNode.formData.title ?? this.state.formElement.title}
                </span>
                <span className={'tlbm-form-settings-print-subtitle'}>
                    {this.state.formNode.formData.name ?? ""}
                </span>
                {this.state.formNode.formData.required == "yes" ? (
                    <span className={"tlbm-form-settings-print-required"}>
                        {Localization.getText("Required")}
                    </span>
                ) : null}
            </div>
        );
    }
}