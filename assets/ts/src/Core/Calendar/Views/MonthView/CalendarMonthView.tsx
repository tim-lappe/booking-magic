import * as React from "react";
import {CalendarBaseState, CalendarComponentBase} from "../CalendarComponentBase";
import {CalendarMonthViewSetting} from "../../../Entity/CalendarMonthViewSetting";
import {DateLocalization} from "../../../../DateLocalization";
import {DateTime} from "../../../Adapter/DateTime";
import {MonthViewDateCell} from "./MonthViewDateCell";
import {MergedActionsRequest} from "../../../Ajax/MergedActionsRequest";


interface CalendarMonthViewState {
    selectedDate?: DateTime;
    smallVersion: boolean;
    contentReady: boolean;
}

export class CalendarMonthView extends CalendarComponentBase<CalendarMonthViewSetting, CalendarMonthViewState> {

    private calendarDiv = React.createRef<HTMLDivElement>();

    constructor(props) {
        super(props);

        this.onClickNextMonth = this.onClickNextMonth.bind(this);
        this.onClickPrevMonth = this.onClickPrevMonth.bind(this);
        this.onClickOnDateTile = this.onClickOnDateTile.bind(this);

        this.state = {
            focusedDate: this.state.focusedDate,
            viewState: {
                selectedDate: null,
                smallVersion: false,
                contentReady: false
            }
        }
    }

    protected prepareUpdateBookingOptions(calendarReuqest: MergedActionsRequest): MergedActionsRequest {
        return calendarReuqest;
    }

    getDayTiles(): any[] {
        let firstDateThisMonth = this.state.focusedDate.getFirstDayThisMonth();
        let lastDateThisMonth = this.state.focusedDate.getLastDayThisMonth();

        let daysThisMonth = this.state.focusedDate.getDaysAsDateTimesInMonth();
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
            prevState.focusedDate.addMonth(1);
            return prevState;
        }, () => {
            this.updateBookingOptions();
        });

        event.preventDefault();
    }

    onClickPrevMonth(event: any) {
        this.setState((prevState) => {
            prevState.focusedDate.addMonth(-1);
            return prevState;
        }, () => {
            this.updateBookingOptions();
        });

        event.preventDefault();
    }

    onClickOnDateTile(date: DateTime) {
        this.setState((prevState: CalendarBaseState<CalendarMonthViewState>) => {
            prevState.viewState.selectedDate = date;
            return prevState;
        }, () => {
            this.updateBookingOptions();
        });
    }

    componentDidMount() {
        let bounding = this.calendarDiv.current.getBoundingClientRect();
        let emSize = getComputedStyle(this.calendarDiv.current).fontSize;
        let emSizenum = parseInt( emSize.replace("px", ""));
        if(bounding.width < (emSizenum * 2) * 7 * 2.5) {
            this.setState((prevState ) => {
                prevState.viewState.smallVersion = true;
                return prevState;
            });
        }

        this.setState((prevState) => {
            prevState.viewState.contentReady = true;
            return prevState;
        })

        super.componentDidMount();
    }

    render() {
        let dayTiles = this.getDayTiles();
        let weekdayTiles = [...this.props.viewSettings.weekday_labels];

        let columnSize = (1 / weekdayTiles.length) * 100;
        let cssGridColumns = "";
        for(let i = 0; i < weekdayTiles.length; i++) {
            cssGridColumns += columnSize + "% ";
        }

        let labelThisMonth = DateLocalization.GetMonthLabelByNum(this.state.focusedDate.getMonth());
        let dateNextMonth = DateTime.copy(this.state.focusedDate);
        dateNextMonth.addMonth(1);
        let labelNextMonth =  DateLocalization.GetMonthLabelByNum(dateNextMonth.getMonth()) + " " + dateNextMonth.getYear();

        let datePrevMonth = DateTime.copy(this.state.focusedDate);
        datePrevMonth.addMonth(-1);
        let labelPrevMonth =  DateLocalization.GetMonthLabelByNum(datePrevMonth.getMonth()) + " " + datePrevMonth.getYear();

        if(this.state.viewState.smallVersion) {
            labelNextMonth = ">";
            labelPrevMonth = "<";

            for(let i = 0; i < weekdayTiles.length; i++) {
                weekdayTiles[i] = weekdayTiles[i].substr(0, 1);
            }
        }

        let today = DateTime.create();
        today.setHourMin(0, 0);

        return (
            <div ref={this.calendarDiv} className={"tlbm-calendar-month-view " + (this.state.viewState.smallVersion ? "tlbm-calendar-small" : "")}>
                {this.state.viewState.contentReady ? (
                    <React.Fragment>
                        <div className={"tlbm-month-view-header"}>
                            <button onClick={this.onClickPrevMonth} className={"button button-primary tlbm-button-calendar-month-traverse"}>{labelPrevMonth}</button>
                            <span className={"tlbm-month-view-current-month"}>{labelThisMonth} {this.state.focusedDate.getYear()}</span>
                            <button onClick={this.onClickNextMonth} className={"button button-primary tlbm-button-calendar-month-traverse"}>{labelNextMonth}</button>
                        </div>
                        <div className='tlbm-calendar-table'  style={{gridTemplateColumns: cssGridColumns}}>
                            {weekdayTiles.map((label, index) => {
                                return (
                                    <div className={"tlbm-head-weekday tlbm-head-weekday-" + index} key={index}>{label}</div>
                                );
                            })}
                            {dayTiles.map((date: any, index) => {
                                if(date instanceof DateTime && this.state.bookingOptions != null) {
                                    let dateCapacity = this.state.bookingOptions?.getMergedActionsForDay(date).getActionResultValue("dateCapacity");
                                    let cellDisabled = dateCapacity == null || dateCapacity == 0;

                                    return (
                                        <MonthViewDateCell
                                            disabled={cellDisabled}
                                            empty={false} onClick={this.onClickOnDateTile}
                                            selected={this.state.viewState.selectedDate != null && DateTime.isSameDay(this.state.viewState.selectedDate, date)}
                                            dateTime={date} key={index}>
                                            <span style={{float: "right", visibility: (!cellDisabled ? "visible" : "hidden")}}>{dateCapacity}</span>

                                        </MonthViewDateCell>
                                    )
                                } else {
                                    return (
                                        <MonthViewDateCell empty={true} onClick={this.onClickOnDateTile} key={index} />
                                    )
                                }
                            })}
                        </div>
                    </React.Fragment>
                ): null}
            </div>
        );
    }
}