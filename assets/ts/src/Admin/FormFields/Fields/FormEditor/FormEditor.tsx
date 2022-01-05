import * as React from "react";
import {Utils} from "../../../../Utils";
import {FormElement} from "../../../Entity/FormElement";
import {Localization} from "../../../../Localization";
import {FormEditorEntity} from "./FormEditorEntity";
import {FormData} from "../../../Entity/FormData";
import {FormElementsManager} from "./FormElementsManager";
import {SelectFormElementWindow} from "./SelectFormElementWindow/SelectFormElementWindow";
import {ElementSettingsWindow} from "./SettingsWindow/ElementSettingsWindow";


interface FormEditorProps {
    dataset: any;
}

interface FormEditorState {
    formDataList: FormData[];
    isAddElementWindowShowing: boolean;
    settingsWindowFormData?: FormData;
    settingsWindowFormElement?: FormElement;
}

export class FormEditor extends React.Component<FormEditorProps, FormEditorState> {

    /**
     * Definition of Editor Form Fields
     *
     */
    public formElementsManager: FormElementsManager;

    constructor(props) {
        super(props);
        this.onClickAddElement = this.onClickAddElement.bind(this);
        this.onElementFromAddElementWindowSelected = this.onElementFromAddElementWindowSelected.bind(this);
        this.onAddElementWindowClosed = this.onAddElementWindowClosed.bind(this);
        this.addElementToRoot = this.addElementToRoot.bind(this);
        this.onSettingsWindowClosed = this.onSettingsWindowClosed.bind(this);
        this.openSettingsWindow = this.openSettingsWindow.bind(this);

        let jsondata = Utils.decodeUriComponent(props.dataset.json);
        jsondata = JSON.parse(jsondata);

        let formData: any[] = [];
        if(Array.isArray(jsondata)) {
            formData = jsondata;
        }

        let formfields = Utils.decodeUriComponent(props.dataset.fields);
        formfields = JSON.parse(formfields);
        if(Array.isArray(formfields)) {
            this.formElementsManager = new FormElementsManager(formfields);
        } else {
            this.formElementsManager = new FormElementsManager([]);
            console.log("No Formfields loaded");
        }

        this.state = {
            formDataList: formData,
            isAddElementWindowShowing: false,
            settingsWindowFormData: null
        }
    }

    onElementFromAddElementWindowSelected(formElement: FormElement) {
        this.setState((prevState: FormEditorState) => {
            prevState.isAddElementWindowShowing = false;
            return prevState;
        });

        let formData = new FormData();
        formData.unique_name = formElement.unique_name;

        this.addElementToRoot(formData);
    }

    onAddElementWindowClosed() {
        this.setState((prevState: FormEditorState) => {
            prevState.isAddElementWindowShowing = false;
            return prevState;
        });
    }

    onClickAddElement(event: any) {
        this.setState((prevState: FormEditorState) => {
            prevState.isAddElementWindowShowing = true;
            return prevState;
        });

        event.preventDefault();
    }

    addElementToRoot(formData: FormData) {
        this.setState((prevState: FormEditorState) => {
            prevState.formDataList = [...prevState.formDataList, formData];
            return prevState;
        });
    }

    openSettingsWindow(formData: FormData) {
        this.setState((prevState: FormEditorState) => {
            prevState.settingsWindowFormData = formData;
            prevState.settingsWindowFormElement = this.formElementsManager.getFormElementByName(formData.unique_name);
            return prevState;
        });
    }

    onSettingsWindowClosed() {
        this.setState((prevState: FormEditorState) => {
            prevState.settingsWindowFormData = null;
            prevState.settingsWindowFormElement = null;
            return prevState;
        });
    }

    render() {
        return (
            <div className={"tlbm-form-editor"}>
                <SelectFormElementWindow onCancel={this.onAddElementWindowClosed} onElementSelected={this.onElementFromAddElementWindowSelected} show={this.state.isAddElementWindowShowing} formElementsManager={this.formElementsManager} />
                <ElementSettingsWindow formElement={this.state.settingsWindowFormElement} formData={this.state.settingsWindowFormData} onCancel={this.onSettingsWindowClosed} />
                <div className={"tlbm-form-item-container"}>
                    {this.state.formDataList.map((dataItem, index) => {
                        return (
                            <FormEditorEntity key={index} formEditor={this} formData={dataItem} />
                        );
                    })}
                </div>
                <button onClick={this.onClickAddElement} className={"button button-primary tlbm-button-add-element"}>{Localization.__("Add Element")}</button>
            </div>
        );
    }
}