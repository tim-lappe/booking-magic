import * as React from "react";
import {Localization} from "../../../../Localization";

interface MonthViewTimePanelProps {
    fullDay?: { capacityRemaining: number, capacityOriginal: number };
    times: { hour: number, minute: number, capacityRemaining: number, capacityOriginal: number }[];
    onSelect?: (time: { hour: number, minute: number, capacityRemaining: number, capacityOriginal: number }) => void;
}

interface MonthViewTimePanelState {
    selected?: {hour: number, minute: number, capacityRemaining: number, capacityOriginal: number};
}


export class MonthViewTimePanel extends React.Component<MonthViewTimePanelProps, MonthViewTimePanelState> {


    constructor(props) {
        super(props);

        this.onClickOnTimeTile = this.onClickOnTimeTile.bind(this);
        this.onClickFullDay = this.onClickFullDay.bind(this);

        this.state = {
            selected: null
        }
    }

    getWithLeadingZero(time) {
        if(parseInt(time) < 10) {
            return "0" + time;
        }
        return time;
    }

    onClickOnTimeTile(event: any, timeCapacity: any) {
        if (timeCapacity.capacityRemaining > 0) {
            if (this.props.onSelect) {
                this.props.onSelect(timeCapacity);
            }

            this.setState((prevState: MonthViewTimePanelState) => {
                prevState.selected = timeCapacity;
                return prevState;
            });
        }

        event.preventDefault();
    }

    onClickFullDay(event: any) {
        this.setState((prevState: MonthViewTimePanelState) => {
            prevState.selected = null;
            return prevState;
        });

        event.preventDefault();
    }

    getTimeTile(timeCapacity: { hour: number, minute: number, capacityRemaining: number, capacityOriginal: number }) {
        return (
            <div
                className={"tlbm-calendar-time-slot " + (this.state.selected == timeCapacity ? "tlbm-time-cell-selected " : "") + (timeCapacity.capacityRemaining <= 0 ? " tlbm-time-cell-no-capacities" : "")}
                onClick={(e) => this.onClickOnTimeTile(e, timeCapacity)}
                key={timeCapacity.hour + "_" + timeCapacity.minute}
                style={{display: timeCapacity.capacityOriginal > 0 ? "flex" : "none"}}>
                <span>{this.getWithLeadingZero(timeCapacity.hour)}:{this.getWithLeadingZero(timeCapacity.minute)}</span>
                <span className={"dashicons dashicons-yes-alt"}
                      style={{display: this.state.selected == timeCapacity ? "block" : "none"}}/>
            </div>
        )
    }

    render() {
        return (
            <div className={"tlbm-calendar-time-panel "}
                 style={{display: this.props.times.length > 0 ? "grid" : "none"}}>
                {this.props.fullDay != null ? (
                    <div
                        className={"tlbm-calendar-time-slot " + (this.state.selected == null ? "tlbm-time-cell-selected " : "") + (this.props.fullDay.capacityRemaining <= 0 ? " tlbm-time-cell-no-capacities" : "")}
                        onClick={this.onClickFullDay}
                        style={{display: this.props.fullDay.capacityRemaining > 0 ? "flex" : "none"}}>

                        {Localization.getText("All Day")}
                        <span className={"dashicons dashicons-yes-alt"}
                              style={{display: this.state.selected == null ? "block" : "none"}}/>
                    </div>
                ) : null}
                {this.props.times.map((timeCapacity) => {
                    return (
                        this.getTimeTile(timeCapacity)
                    )
                })}
            </div>
        );
    }
}