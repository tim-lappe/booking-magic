import * as React from "react";
import {FormEditor} from "./FormEditor";
import {FormData} from "../../../Entity/FormData";
import {FormElement} from "../../../Entity/FormElement";

interface FormEditorElementProps {
    formEditor: FormEditor;
    formData: FormData;
}

interface FormEditorElementState {
    formData: FormData;
}

export class FormEditorEntity extends React.Component<FormEditorElementProps, FormEditorElementState> {

    public formElement?: FormElement;

    constructor(props) {
        super(props);
        this.onClick = this.onClick.bind(this);

        this.state = {
            formData: this.props.formData
        }

        this.formElement = this.props.formEditor.formElementsManager.getFormElementByName(this.state.formData.unique_name);
    }

    onClick(event: any) {
        this.props.formEditor.openSettingsWindow(this.state.formData);
        event.preventDefault();
    }

    componentDidUpdate(prevProps: Readonly<FormEditorElementProps>, prevState: Readonly<FormEditorElementState>, snapshot?: any) {
        this.formElement = this.props.formEditor.formElementsManager.getFormElementByName(this.state.formData.unique_name);
    }

    render() {
        return (
            <div onClick={this.onClick} className={"tlbm-draggable tlbm-form-item-container"} draggable={true}>
                {this.props.formEditor.formElementsManager.createElementComponent(this.formElement, this.state.formData)}
            </div>
        )
    }
}