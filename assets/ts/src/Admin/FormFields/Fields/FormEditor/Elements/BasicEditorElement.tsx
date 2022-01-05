import * as React from "react";
import {FormElement} from "../../../../Entity/FormElement";
import {FormData} from "../../../../Entity/FormData";

interface BasicEditorElementProps {
    formElement: FormElement;
    formData: FormData;
}

interface BasicEditorElementState {
    formData: FormData;
}

export class BasicEditorElement extends React.Component<BasicEditorElementProps, BasicEditorElementState> {

    constructor(props) {
        super(props);

        this.state = {
            formData: this.props.formData
        }
    }

    render() {
        return (
            <div>
                <div className={'tlbm-form-item-box'}>
                    <span className={'tlbm-form-settings-print-title'}>
                        {this.props.formElement.title}
                    </span>
                    <span className={'tlbm-form-settings-print-subtitle'}>
                        {this.props.formData.name ?? ""}
                    </span>
                </div>
            </div>
        );
    }
}