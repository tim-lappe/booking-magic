import * as React from "react";
import {FormElement} from "../../../Entity/FormEditor/FormElement";
import {FormElementData} from "../../../Entity/FormEditor/FormElementData";
import {SettingsWindowItem} from "./SettingsWindowItem";
import {Localization} from "../../../../Localization";
import {SettingsTypeManager} from "./SettingsTypeManager";
import {FormEditorNode} from "../../../Entity/FormEditor/FormEditorNode";
import {Editor} from "../Editor";
import {createRef, Fragment} from "react";

interface ElementSettingsWindowProps {
    onCancel?: () => void;
    onRemove?: (formNode: FormEditorNode) => void;
    onApply?: (formDataCopy: FormElementData, formNode: FormEditorNode) => void;
    formEditor: Editor;
}

interface ElementSettingsWindowState {
    formDataCopy?: FormElementData;
    formElement?: FormElement;
    formNode?: FormEditorNode;
    errorMessage: any;
    rand: number;
}

export class ElementSettingsWindow extends React.Component<ElementSettingsWindowProps, ElementSettingsWindowState> {

    public settingsTypeManager: SettingsTypeManager;

    constructor(props) {
        super(props);

        this.onBackgroundClicked = this.onBackgroundClicked.bind(this);
        this.onSettingChanged = this.onSettingChanged.bind(this);
        this.onApplyClicked = this.onApplyClicked.bind(this);
        this.onCancelClicked = this.onCancelClicked.bind(this);
        this.onRemoveClicked = this.onRemoveClicked.bind(this);

        this.settingsTypeManager = new SettingsTypeManager();

        this.state = {
            rand: Math.random(),
            errorMessage: {}
        }
    }

    public open(formNode: FormEditorNode, formElement: FormElement) {
        let formDataCopy = new FormElementData();
        Object.assign(formDataCopy, formNode.formData);

        this.setState((prevState: ElementSettingsWindowState) => {
            prevState.formDataCopy = formDataCopy;
            prevState.formElement = formElement;
            prevState.formNode = formNode;
            prevState.errorMessage = {};
            prevState.rand = Math.random();

            return prevState;
        });
    }

    public close() {
        this.setState((prevState: ElementSettingsWindowState) => {
            prevState.formDataCopy = null;
            return prevState;
        });
    }

    onBackgroundClicked(event: any) {
        if(event.target.classList.contains("tlbm-form-editor-element-settings-window")) {
            if (this.props.onCancel != null) {
                this.props.onCancel();
            }
        }

        event.preventDefault();
    }

    onCancelClicked(event: any) {
        if (this.props.onCancel != null) {
            this.props.onCancel();
        }

        event.preventDefault();
    }

    onApplyClicked(event: any) {
        if (this.props.onApply != null) {
            this.props.onApply(this.state.formDataCopy, this.state.formNode);
        }

        event.preventDefault();
    }

    onRemoveClicked(event: any) {
        if (this.props.onRemove != null) {
            this.props.onRemove(this.state.formNode);
        }

        event.preventDefault();
    }

    onSettingChanged(index: number, formData: FormElementData) {
        this.setState((prevState: ElementSettingsWindowState) => {
            prevState.formDataCopy = formData;
            return prevState;
        });
    }

    onErrorChanged(index: number, formData: FormElementData, errorMessage: string) {
        this.setState((prevState: ElementSettingsWindowState) => {
            if (errorMessage.length > 0) {
                prevState.errorMessage[index] = errorMessage;
            } else {
                delete prevState.errorMessage[index];
            }
            return prevState;
        });
    }

    hasErrors() {
        return Object.entries(this.state.errorMessage).length > 0
    }

    render() {
        if(this.state.formElement != null) {
            let settings = FormElement.getSettingsWithCategory(this.state.formElement);
            let categories = FormElement.getSettingsCategories(this.state.formElement);

            return (
                <div onClick={this.onBackgroundClicked}
                     style={{display: this.state.formDataCopy != null && this.state.formElement != null ? "flex" : "none"}}
                     className={"tlbm-form-editor-element-settings-window tlbm-window-outer"}>
                    <div className={"tlbm-element-settings-window-inner tlbm-window-inner"}>
                        <div className={"tlbm-form-settings-top-bar"}>
                            <h3>{this.state.formElement?.title}</h3>
                            <button onClick={this.onRemoveClicked}
                                    className={"button tlbm-button-danger"}>{Localization.__("Remove")}</button>
                            <button onClick={this.onCancelClicked}
                                    className={"button tlbm-button-white"}>{Localization.__("Cancel")}</button>
                            <button disabled={this.hasErrors()} onClick={this.onApplyClicked}
                                    className={"button button-primary"}>{Localization.__("Apply")}</button>
                        </div>
                        <div className={"tlbm-form-settings-container"}>
                            {categories.map((category) => {
                                return (
                                    <React.Fragment key={category}>
                                        <h3>{category}</h3>
                                        <div className={"tlbm-form-settings-items-collection"}>
                                            {settings[category].map((elementSetting, index) => {
                                                return (
                                                    <SettingsWindowItem formNode={this.state.formNode} window={this}
                                                                        key={elementSetting.name + this.state.rand}
                                                                        onErrorUpdate={(formData: FormElementData, errorString: string) => this.onErrorChanged(index, formData, errorString)}
                                                                        onChange={(formData: FormElementData) => this.onSettingChanged(index, formData)}
                                                                        elementSetting={elementSetting}
                                                                        formData={this.state.formDataCopy}/>
                                                );
                                            })}
                                        </div>
                                    </React.Fragment>
                                )
                            })}
                        </div>
                    </div>
                </div>
            );
        }

        return (
            <div />
        )
    }
}