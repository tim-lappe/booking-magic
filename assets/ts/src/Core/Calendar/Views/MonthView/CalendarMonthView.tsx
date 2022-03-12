import * as React from "react";
import {CalendarBaseState, CalendarComponentBase} from "../CalendarComponentBase";
import {DateLocalization} from "../../../../DateLocalization";
import {DateTime} from "../../../Adapter/DateTime";
import {MonthViewDateCell} from "./MonthViewDateCell";
import {MergedActionsRequest} from "../../../Ajax/MergedActionsRequest";
import {MonthViewTimePanel} from "./MonthViewTimePanel";


interface CalendarMonthViewState {
    selectedDate?: DateTime;
    smallVersion: boolean;
    contentReady: boolean;
}

export class CalendarMonthView extends CalendarComponentBase<CalendarMonthViewState> {

    private calendarDiv = React.createRef<HTMLDivElement>();

    constructor(props) {
        super(props);

        this.onClickNextMonth = this.onClickNextMonth.bind(this);
        this.onClickPrevMonth = this.onClickPrevMonth.bind(this);
        this.onClickOnTimeTile = this.onClickOnTimeTile.bind(this);
        this.onClickOnDateTile = this.onClickOnDateTile.bind(this);

        this.state = {
            focusedDate: this.state.focusedDate,
            formValue: null,
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
        daysThisMonth.forEach((date) => date.setFullDay(true));
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
            prevState.formValue = date;
            return prevState;
        });
    }

    onClickOnTimeTile(time: {hour: number, minute: number, capacityOriginal: number, capacityRemaining: number}) {
        this.setState((prevState) => {
            if(prevState.viewState.selectedDate != null) {
                prevState.viewState.selectedDate.setFullDay(false);
                prevState.viewState.selectedDate.setHourMin(time.hour, time.minute, 0);
                return prevState;
            }
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
        let weekdayTiles = [...this.props.display.viewSettings.weekday_labels];

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

        let timeCapacities = [];
        if(this.state.viewState.selectedDate && this.state.bookingOptions != null) {
            let timeCapsSelected = this.state.bookingOptions?.getMergedActionsForDay(this.state.viewState.selectedDate).getActionResultValue("timeCapacities")?.timeSlotsCapacities;
            if(Array.isArray(timeCapsSelected)) {
                timeCapacities = timeCapsSelected;
            }
        }

        let today = DateTime.create();
        today.setHourMin(0, 0);

        return (
            <div ref={this.calendarDiv} className={"tlbm-calendar-month-view " + (this.state.viewState.smallVersion ? "tlbm-calendar-small" : "")}>
                {this.state.viewState.contentReady ? (
                    <React.Fragment>
                        <input type={"hidden"} value={this.getEncodedValue()} name={this.props.display.inputName}/>
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
                                    let dateCapacity = this.state.bookingOptions?.getMergedActionsForDay(date).getActionResultValue("dateCapacity")?.capacityRemaining;
                                    let timeCapacities = this.state.bookingOptions?.getMergedActionsForDay(date).getActionResultValue("timeCapacities")?.timeSlotsCapacities;
                                    let cellDisabled = (dateCapacity == null || dateCapacity == 0);

                                    if (timeCapacities != null && timeCapacities.length > 1) {
                                        for (let timeCap of timeCapacities) {
                                            if (timeCap.capacityRemaining > 0) {
                                                cellDisabled = false;
                                            }
                                        }
                                    }

                                    return (
                                        <MonthViewDateCell
                                            disabled={cellDisabled}
                                            empty={false} onClick={this.onClickOnDateTile}
                                            selected={!cellDisabled && this.state.viewState.selectedDate != null && DateTime.isSameDay(this.state.viewState.selectedDate, date)}
                                            dateTime={date} key={index}>
                                            <span style={{
                                                float: "right",
                                                visibility: (!cellDisabled ? "visible" : "hidden")
                                            }}>{dateCapacity}</span>
                                        </MonthViewDateCell>
                                    )
                                } else {
                                    return (
                                        <MonthViewDateCell empty={true} onClick={this.onClickOnDateTile} key={index} />
                                    )
                                }
                            })}

                            <MonthViewTimePanel onSelect={this.onClickOnTimeTile} times={timeCapacities} />
                        </div>
                    </React.Fragment>
                ): null}
            </div>
        );
    }
}