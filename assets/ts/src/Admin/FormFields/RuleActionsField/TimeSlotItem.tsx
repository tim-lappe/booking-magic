import React = require("react");
import {RuleActionItemBase} from "./RuleActionItemBase";

export class TimeSlotItem extends RuleActionItemBase {

    protected getFields(): JSX.Element {
        return (
            <React.Fragment>
                {this.getTimeSlotFields()}
                {this.getCapacityFields()}
            </React.Fragment>
        );
    }
}