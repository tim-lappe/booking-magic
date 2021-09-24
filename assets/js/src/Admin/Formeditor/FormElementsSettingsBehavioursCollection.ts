import {TitleBehaviour} from "./Entities/SettingsBehaviours/TitleBehaviour";
import {FormElementSettingsBehaviour} from "./Entities/FormElementSettingsBehaviour";

export default class FormElementsSettingsBehavioursCollection {

    private static behaviourList: FormElementSettingsBehaviour[] = [];
    public static registerSettingsBehaviours() {
        this.behaviourList = [];
        this.behaviourList.push(new TitleBehaviour());
    }

    public static getList(): FormElementSettingsBehaviour[] {
        return this.behaviourList;
    }

    public static callOnSettingsWindowOpened(args: any) {
        this.getList().forEach((behaviour) => {
           behaviour.onSettingsWindowOpened(args);
        });
    }

    public static callOnSettingsChanged(args: any) {
        this.getList().forEach((behaviour) => {
            behaviour.onSettingsChanged(args);
        });
    }
}