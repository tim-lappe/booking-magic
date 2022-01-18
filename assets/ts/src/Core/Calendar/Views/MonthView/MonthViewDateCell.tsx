import * as React from "react";
import {DateTime} from "../../../DateTime";

interface MonthViewCellProps {
    empty?: boolean;
    dateTime?: DateTime;
    disabled?: boolean;
    selected?: boolean;
    onClick?: (dateTime: DateTime) => void;
}

interface MonthViewCellState {

}

export class MonthViewDateCell extends React.Component<MonthViewCellProps, MonthViewCellState> {

    constructor(props) {
        super(props);

        this.state = {
            selected: false
        }
    }

    onClickOnDateTile(event: any) {
        if(this.props.onClick && !this.props.disabled) {
            this.props.onClick(this.props.dateTime);
        }

        event.preventDefault();
    }

    render() {
        if(this.props.dateTime && !this.props.empty) {
            return (
                <div onClick={(event) => this.onClickOnDateTile(event)}  className={"tlbm-cell " + (this.props.dateTime.isDayNow() ? "tlbm-cell-today " : " ") + (this.props.selected ? "tlbm-cell-selected ": "") + (!this.props.disabled ? "tlbm-cell-selectable tlbm-cell-not-empty " : "")}>
                    <span className={"tlbm-datenumber-span"}>{this.props.dateTime.getMonthDay()}</span>
                    <span className={"dashicons dashicons-yes-alt"} style={{display: this.props.selected ? "block": "none"}} />
                    {this.props.children}
                </div>
            );
        } else {
            return (
                <div className={"tlbm-cell"} />
            )
        }
    }
}