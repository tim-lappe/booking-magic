import {DateTime} from "../../Adapter/DateTime";

export class MergedRuleActionsSet {

    /**
     *
     */
    public dateTime: DateTime;

    /**
     *
     * @private
     */
    private mergedActions: any[] = [];

    constructor() {

    }

    public add(mergedAction: any) {
        this.mergedActions.push(mergedAction);
    }

    public getAction<T>(type: { new(): T; }, name: string): T {
        for(let action of this.mergedActions) {
            if (action[name] != null) {
                let instance = new type();
                Object.assign(instance, action[name]);
                console.log(instance);
                return instance;
            }
        }
        return null;
    }
}