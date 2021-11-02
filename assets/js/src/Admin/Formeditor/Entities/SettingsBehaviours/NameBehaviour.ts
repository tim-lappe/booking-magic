import {FormElementSettingsBehaviour} from "../FormElementSettingsBehaviour";

export class NameBehaviour extends FormElementSettingsBehaviour {
    onSettingsChanged(args: any): void {
        let inputnamelem = document.querySelector(".tlbm-form-editor-element-settings-window [name='name']") as HTMLInputElement;

    }

    onSettingsWindowOpened(args: any): void {

    }

}