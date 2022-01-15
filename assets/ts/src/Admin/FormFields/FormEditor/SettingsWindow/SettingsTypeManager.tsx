import {BasicSettingsTypeElement} from "./SettingTypes/BasicSettingsTypeElement";
import * as React from "react";
import {ElementSetting} from "../../../Entity/FormEditor/ElementSetting";
import {SelectSettingsType} from "./SettingTypes/SelectSettingsType";
import {TextareaSettingsType} from "./SettingTypes/TextareaSettingsType";

export class SettingsTypeManager {
    readonly settingsTypeComponents: Map<string, typeof BasicSettingsTypeElement>;

    constructor() {
        this.settingsTypeComponents = new Map<string, typeof BasicSettingsTypeElement>();
        this.settingsTypeComponents.set("select", SelectSettingsType);
        this.settingsTypeComponents.set("textarea", TextareaSettingsType);
    }

    public createSettingsTypeComponent(elementSetting: ElementSetting, value: any, onChange: (oldVal: any, newVal: any) => void): JSX.Element {
        let Components = this.settingsTypeComponents;
        if(Components.has(elementSetting.type)) {
            const ElementComponent = Components.get(elementSetting.type);
            return <ElementComponent elementSetting={elementSetting} value={value} onChange={onChange} />;
        }

        return <BasicSettingsTypeElement elementSetting={elementSetting} value={value} onChange={onChange} />;
    }

    public getSettingsTypeComponentsWithCategory() {

    }
}