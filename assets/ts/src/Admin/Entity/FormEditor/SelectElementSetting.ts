import {ElementSetting} from "./ElementSetting";

export class SelectElementSetting extends ElementSetting {

    public key_values: any;

    public findLabel(search_value: any): string {
        for(let [val, label] of Object.entries(this.key_values)) {
            if(val == search_value) {
                return label.toString();
            }

            if(!(typeof label == "string" || typeof label == "number")) {
                for(let [sub_val, sub_label] of Object.entries(label)) {
                    if(sub_val == search_value) {
                        return sub_label.toString();
                    }
                }
            }
        }
    }
}