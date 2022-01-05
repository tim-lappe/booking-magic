import * as React from "react";
import {FormElement} from "../../../../Entity/FormElement";
import {FormData} from "../../../../Entity/FormData";
import {SettingsWindowItem} from "./SettingsWindowItem";
import {Localization} from "../../../../../Localization";

interface ElementSettingsWindowProps {
    formData?: FormData;
    formElement?: FormElement;
    onCancel?: () => void;
}

interface ElementSettingsWindowState {

}

export class ElementSettingsWindow extends React.Component<ElementSettingsWindowProps, ElementSettingsWindowState> {

    constructor(props) {
        super(props);

        this.onBackgroundClicked = this.onBackgroundClicked.bind(this);
    }

    onBackgroundClicked(event: any) {
        if(event.target.classList.contains("tlbm-form-editor-element-settings-window")) {
            if (this.props.onCancel != null) {
                this.props.onCancel();
            }
        }

        event.preventDefault();
    }

    render() {
        return (
            <div onClick={this.onBackgroundClicked} style={{display: this.props.formData != null && this.props.formElement != null  ? "flex" : "none"}} className={"tlbm-form-editor-element-settings-window tlbm-window-outer"}>
                <div className={"tlbm-element-settings-window-inner tlbm-window-inner"}>
                    <div className={"tlbm-form-settings-top-bar"}>
                        <h3>{this.props.formElement?.title}</h3>
                        <button className={"button tlbm-button-white"}>{Localization.__("Cancel")}</button>
                        <button className={"button button-primary"}>{Localization.__("Apply")}</button>
                    </div>
                    <div className={"tlbm-form-settings-container"}>
                        <div className={"tlbm-form-settings-items-collection"}>
                            {this.props.formElement?.settings.map((settingsType) => {
                                return (
                                    <SettingsWindowItem settingsType={settingsType} formData={this.props.formData} />
                                );
                            })}
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}