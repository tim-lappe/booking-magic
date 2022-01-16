import * as React from "react";
import {CalendarBaseState, CalendarComponentBase} from "../CalendarComponentBase";
import {CalendarMonthViewSetting} from "../../../Entity/CalendarMonthViewSetting";
import {Localization} from "../../../../Localization";
import {DateLocalization} from "../../../../DateLocalization";
import {DateTime} from "../../../DateTime";


interface CalendarMonthViewState {

}

export class CalendarMonthView extends CalendarComponentBase<CalendarMonthViewSetting, CalendarMonthViewState> {

    constructor(props) {
        super(props);

        this.onClickNextMonth = this.onClickNextMonth.bind(this);
        this.onClickPrevMonth = this.onClickPrevMonth.bind(this);
    }

    getDayTiles(): any[] {
        let firstDateThisMonth = this.state.focusedDate.getFirstDayThisMonth();
        let lastDateThisMonth = this.state.focusedDate.getLastDayThisMonth();

        let daysThisMonth = DateTime.getDatesBetween(firstDateThisMonth, lastDateThisMonth);
        let dayTiles = [];

        for(let i = 1; i < firstDateThisMonth.getWeekday(); i++) {
            dayTiles.push("empty");
        }

        dayTiles.push(...daysThisMonth);

        for(let i = lastDateThisMonth.getWeekday(); i < 7; i++) {
            dayTiles.push("empty");
        }

        return dayTiles;
    }

    onClickNextMonth(event: any) {
        this.setState((prevState: CalendarBaseState<CalendarMonthViewState>) => {
            console.log(prevState.focusedDate);

            prevState.focusedDate.addMonth(1);
            console.log(prevState.focusedDate);
            return prevState;
        });

        event.preventDefault();
    }

    onClickPrevMonth(event: any) {
        this.setState((prevState) => {
            prevState.focusedDate.addMonth(-1);
            return prevState;
        });

        event.preventDefault();
    }

    render() {

        let dayTiles = this.getDayTiles();
        let weekdayTiles = this.props.viewSettings.weekday_labels;

        let columnSize = (1 / weekdayTiles.length) * 100;
        let cssGridColumns = "";
        for(let i = 0; i < weekdayTiles.length; i++) {
            cssGridColumns += columnSize + "% ";
        }

        return (
            <div className={"tlbm-calendar-month-view"}>
                <div className={"tlbm-month-view-header"}>
                    <button onClick={this.onClickPrevMonth} className={"button button-primary tlbm-button-calendar-month-traverse"}>{Localization.__("Prev Month")}</button>
                    <span className={"tlbm-month-view-current-month"}>{DateLocalization.GetMonthLabelByNum(this.state.focusedDate.getMonth())} {this.state.focusedDate.getYear()}</span>
                    <button onClick={this.onClickNextMonth} className={"button button-primary tlbm-button-calendar-month-traverse"}>{Localization.__("Next Month")}</button>
                </div>
                <div className='tlbm-calendar-table'  style={{gridTemplateColumns: cssGridColumns}}>
                    {weekdayTiles.map((label, index) => {
                        return (
                            <div className={"tlbm-head-weekday tlbm-head-weekday-" + index} key={label}>{label}</div>
                        );
                    })}
                    {dayTiles.map((tile: any, index) => {
                        if(tile instanceof DateTime) {
                            return (
                                <div className={"tlbm-cell tlbm-cell-selectable tlbm-cell-not-empty " + (tile.isDayNow() ? "tlbm-cell-today" : "")} key={index}>
                                    <span className={"tlbm-datenumber-span"}>{tile.getMonthDay()}</span>
                                </div>
                            )
                        } else {
                            return (
                                <div className={"tlbm-cell"} key={index}>

                                </div>
                            )
                        }
                    })}
                </div>
            </div>
        );
    }
}