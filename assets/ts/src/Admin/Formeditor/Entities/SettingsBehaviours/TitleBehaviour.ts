import {FormElementSettingsBehaviour} from "../FormElementSettingsBehaviour";

export class TitleBehaviour extends FormElementSettingsBehaviour {
    onSettingsChanged(args: any): void {
        let inputnamelem = document.querySelector(".tlbm-form-editor-element-settings-window [name='name']") as HTMLInputElement;
        if(inputnamelem != null) {
            if(inputnamelem.value.length === 0) {
                inputnamelem.value = args.elem.value.toLowerCase()
                    .replaceAll(" ", "_")
                    .replaceAll("-", "")
                    .replaceAll(/[^a-zA-Z0-9]/g,'');
            }
        }
    }

    onSettingsWindowOpened(args: any): void {

    }
}