import {DateTime} from "../Adapter/DateTime";
import {MergedRuleActionsSet} from "./RuleActions/MergedRuleActionsSet";

export class MergedActions {
    [props: string]: any;

    public actionsResult: any[];

    constructor(from_data: any = null) {
        if(from_data) {
            Object.assign(this, from_data);
        }
    }

    public getMergedActionsForDay(dateTime: DateTime): MergedRuleActionsSet {
        if(this.actionsResult) {
            let actionSet = new MergedRuleActionsSet();
            actionSet.dateTime = dateTime;

            for (let result of this.actionsResult) {
                if (DateTime.isSameDay(DateTime.fromObj(result.dateTime), dateTime)) {
                    actionSet.add(result.mergedActions);
                }
            }

            return actionSet;
        }

        return new MergedRuleActionsSet();
    }
}