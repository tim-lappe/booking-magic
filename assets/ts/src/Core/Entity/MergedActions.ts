import {DateTime} from "../Adapter/DateTime";
import {MergedRuleActionsSet} from "./RuleActions/MergedRuleActionsSet";

export class MergedActions {
    [props: string]: any;

    public actionsResult: any[];

    constructor(fromData: any = null) {
        if(fromData) {
            Object.assign(this, fromData);
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