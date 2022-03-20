import React = require("react");
import {RuleActionItemBase} from "./RuleActionItemBase";
import {Localization} from "../../../../Localization";
import {TimeSelect, TimeSelectTime} from "../../TimeSelect";

export class MultipleTimeSlotItem extends RuleActionItemBase {

    constructor(props) {
        super(props);

        this.onChangeInterval = this.onChangeInterval.bind(this);
        this.onChangeTimeFrom = this.onChangeTimeFrom.bind(this);
        this.onChangeTimeTo = this.onChangeTimeTo.bind(this);

        if (!this.state.ruleAction.actions) {
            this.state.ruleAction.actions = {
                fromHour: 0,
                fromMinute: 0,
                toHour: 23,
                toMinute: 59,
                interval: 5,
                mode: "set",
                amount: 0
            };
        }
    }

    onChangeInterval(event: any) {
        this.setState((prevState) => {
            prevState.ruleAction.actions.interval = event.target.value;

            this.props.onChange(prevState.ruleAction);
            return prevState;
        });

        event.preventDefault();
    }

    onChangeTimeFrom(time: TimeSelectTime) {
        this.setState((prevState) => {
            prevState.ruleAction.actions.fromMinute = time.minute;
            prevState.ruleAction.actions.fromHour = time.hour;

            this.props.onChange(prevState.ruleAction);

            return prevState;
        });
    }

    onChangeTimeTo(time: TimeSelectTime) {
        this.setState((prevState) => {
            prevState.ruleAction.actions.toMinute = time.minute;
            prevState.ruleAction.actions.toHour = time.hour;

            this.props.onChange(prevState.ruleAction);

            return prevState;
        });
    }

    protected getFields(): JSX.Element {
        return (
            <React.Fragment>
                <div style={{marginLeft: "20px"}}>
                    <small>{Localization.__("From")}</small><br/>
                    <TimeSelect initState={{
                        minute: this.state.ruleAction.actions.fromMinute,
                        hour: this.state.ruleAction.actions.fromHour
                    }} minutesSteps={1} onChange={this.onChangeTimeFrom} nameHour={"fromHour"}
                                nameMinute={"fromMinute"}/>
                </div>
                <div style={{marginLeft: "20px"}}>
                    <small>{Localization.__("To")}</small><br/>
                    <TimeSelect initState={{
                        minute: this.state.ruleAction.actions.toMinute,
                        hour: this.state.ruleAction.actions.toHour
                    }} minutesSteps={1} onChange={this.onChangeTimeTo} nameHour={"toHour"} nameMinute={"toMinute"}/>
                </div>
                <div style={{marginLeft: "20px"}}>
                    <small>{Localization.__("Interval")}</small><br/>
                    <select name={"interval"} value={this.state.ruleAction.actions.interval}
                            onChange={this.onChangeInterval}>
                        {Array.from(Array(361).keys()).filter((minute) => minute > 0 && (minute % 2 == 0 || minute % 5 == 0 || minute == 1)).map((minutes: number) => {
                            return (<option key={minutes} value={minutes}>
                                {(minutes + " " + Localization.__("Minutes")).toString()}
                            </option>)
                        })}
                    </select>
                </div>
                {this.getCapacityFields()}
            </React.Fragment>
        );
    }
}