import React = require("react");
import {RuleActionItemBase} from "./RuleActionItemBase";

export class DaySlotItem extends RuleActionItemBase {
    protected getFields(): JSX.Element {
        return (
            <React.Fragment>
                {this.getCapacityFields()}
            </React.Fragment>
        );
    }
}