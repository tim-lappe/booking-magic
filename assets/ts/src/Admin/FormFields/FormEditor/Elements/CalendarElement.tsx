import {BasicEditorElement} from "./BasicEditorElement";
import {FormElement} from "../../../Entity/FormEditor/FormElement";
import * as React from "react";
import {Localization} from "../../../../Localization";
import {SelectElementSetting} from "../../../Entity/FormEditor/SelectElementSetting";

export class CalendarElement extends BasicEditorElement<FormElement> {
    render() {

        let title = this.state.formNode.formData.title ?? this.state.formElement.title;
        title = title.length > 0 ? title : this.state.formElement.title;

        let subtitle = this.state.formNode.formData.name ?? "";

        let selectedCalendar = this.state.formNode.formData.selected_calendar ?? Localization.getText("No calendar selected");
        let selectedCalendarLabel = "";

        let settings = this.state.formElement.settings;
        for(let setting of settings) {
            if(setting.name == "selected_calendar" && setting.type == "select") {
                let selectSetting = new SelectElementSetting();
                selectSetting.keyValues = (setting as SelectElementSetting)?.keyValues;
                if (selectSetting.keyValues != null) {
                    selectedCalendarLabel = selectSetting.findLabel(selectedCalendar);
                }
            }
        }

        return (
            <div className={'tlbm-form-item-box tlbm-form-calendar-box'}>
                <div>
                    <span className={'tlbm-form-settings-print-title'}>
                        {title}
                    </span>
                    <span className={'tlbm-form-settings-print-subtitle'}>
                        {subtitle}
                    </span>
                    {this.state.formNode.formData.required == "yes" ? (
                        <span className={"tlbm-form-settings-print-required"}>
                        {Localization.getText("Required")}
                    </span>
                    ) : null}
                </div>
                <div className={"tlbm-form-calendar-box-calendar-name"}>
                    <span className="dashicons dashicons-calendar-alt" /> {selectedCalendarLabel}
                </div>
            </div>
        )
    }
}