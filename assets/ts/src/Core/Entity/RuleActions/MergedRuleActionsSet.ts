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

    /**
     *
     * @param name
     */
    public getActionResultValue(name: string): any {
        for(let action of this.mergedActions) {
            if (action[name] != null) {
                return action[name];
            }
        }
        return null;
    }
}