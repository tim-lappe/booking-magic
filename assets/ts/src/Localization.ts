declare var tlbm_localization: any;

export class Localization {

    public static __(label: string): string {
        for(const [key, value] of Object.entries(tlbm_localization)) {
            if(label == key) {
                return value.toString();
            }
        }
        return label;
    }

    public static __Arr(label: string): Object {
        for(const [key, value] of Object.entries(tlbm_localization)) {
            if(label == key) {
                return value;
            }
        }
        return label;
    }
}