import React = require("react");
import {RuleActionItemBase} from "./RuleActionItemBase";
import {Localization} from "../../../../Localization";

export class TimeSlotItem extends RuleActionItemBase {

    protected getFields(): JSX.Element {
        return (
            <React.Fragment>
                {this.getTimeSlotFields(Localization.getText("Time"))}
                {this.getCapacityFields()}
            </React.Fragment>
        );
    }
}