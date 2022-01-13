import * as React from "react";
import {FormElementData} from "../../../Entity/FormEditor/FormElementData";
import {ElementSetting} from "../../../Entity/FormEditor/ElementSetting";
import {ElementSettingsWindow} from "./ElementSettingsWindow";
import {Localization} from "../../../../Localization";
import {FormEditorNode} from "../../../Entity/FormEditor/FormEditorNode";


interface SettingsWindowItemProps {
    window: ElementSettingsWindow;
    formData: FormElementData;
    formNode: FormEditorNode;
    elementSetting: ElementSetting;
    onChange: (formData: FormElementData) => void;
    onErrorUpdate: (formData: FormElementData, errorString: string) => void;
}

interface SettingsWindowItemState {
    formData?: FormElementData;
    errorMessage: string;
}

export class SettingsWindowItem extends React.Component<SettingsWindowItemProps, SettingsWindowItemState> {

    constructor(props) {
        super(props);

        this.onChange = this.onChange.bind(this);
        this.checkErrors = this.checkErrors.bind(this);

        this.state = {
            formData: this.props.formData,
            errorMessage: ""
        }
    }

    onChange(newVal: any, oldVal: any) {
        this.setState((prevState: SettingsWindowItemState) => {
            prevState.formData[this.props.elementSetting.name] = newVal;
            if(this.props.onChange) {
                this.props.onChange(prevState.formData);
            }

            return prevState;
        }, () => {
            this.checkErrors();
        });
    }

    componentDidMount() {
        this.checkErrors();
    }

    checkErrors() {
        this.setState((prevState: SettingsWindowItemState) => {
            if(this.props.elementSetting.must_unique) {
                let rootNode = this.props.window?.props.formEditor?.state.rootNode;
                let duplicate = rootNode.findNodesWithData(this.props.elementSetting.name, this.state.formData[this.props.elementSetting.name], this.props.formNode);
                if(duplicate.length > 0) {
                    prevState.errorMessage = Localization.__("This field has to be unique");
                } else {
                    prevState.errorMessage = "";
                }
            }

            if(this.props.onErrorUpdate) {
                this.props.onErrorUpdate(this.state.formData, prevState.errorMessage);
            }

            return prevState;
        });
    }

    render() {
        let val = this.state.formData[this.props.elementSetting.name] ?? this.props.elementSetting.default_value;
        return (
            <div className={"form-item-settings-control"}>
                {this.state.errorMessage.length > 0 ? (
                    <div className={"tlbm-item-settings-error"}>
                        {this.state.errorMessage}
                    </div>
                ): null}
                {this.props.window.settingsTypeManager.createSettingsTypeComponent(this.props.elementSetting, val, this.onChange)}
            </div>
        );
    }
}