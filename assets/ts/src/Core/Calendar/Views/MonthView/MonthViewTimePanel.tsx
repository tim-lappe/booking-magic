import * as React from "react";

interface MonthViewTimePanelProps {
    times: { hour: number, minute: number, capacityRemaining: number, capacityOriginal: number }[];
    onSelect?: (time: {hour: number, minute: number, capacityRemaining: number, capacityOriginal: number}) => void;
}

interface MonthViewTimePanelState {
    selected?: {hour: number, minute: number, capacityRemaining: number, capacityOriginal: number};
}


export class MonthViewTimePanel extends React.Component<MonthViewTimePanelProps, MonthViewTimePanelState> {


    constructor(props) {
        super(props);

        this.onClickOnTimeTile = this.onClickOnTimeTile.bind(this);

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
        if(this.props.onSelect) {
            this.props.onSelect(timeCapacity);
        }

        this.setState((prevState: MonthViewTimePanelState) => {
            prevState.selected = timeCapacity;
            return prevState;
        });

        event.preventDefault();
    }

    render() {
        return (
            <div className={"tlbm-calendar-time-panel "} style={{display: this.props.times.length > 0 ? "grid" : "none"}}>
                {this.props.times.map((timeCapacity) => {
                    return (
                        <div className={"tlbm-calendar-time-slot " + (this.state.selected == timeCapacity ? "tlbm-time-cell-selected ": "")} onClick={(e) => this.onClickOnTimeTile(e, timeCapacity)} key={timeCapacity.hour + "_" + timeCapacity.minute}  style={{display: timeCapacity.capacityOriginal > 0 ? "flex" : "none"}}>
                            <span>{this.getWithLeadingZero(timeCapacity.hour)}:{this.getWithLeadingZero(timeCapacity.minute)}</span>***{timeCapacity.capacityOriginal}
                            <span className={"dashicons dashicons-yes-alt"} style={{display: this.state.selected == timeCapacity ? "block": "none"}} />
                        </div>
                    )
                })}
            </div>
        );
    }
}