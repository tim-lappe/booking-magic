import * as React from "react";
import {DateTime} from "../../Adapter/DateTime";
import {MergedActions} from "../../Entity/MergedActions";
import {CalendarOptions} from "../../Entity/CalendarOptions";
import {MergedActionsRequest} from "../../Ajax/MergedActionsRequest";
import {RequestSet} from "../../Ajax/RequestSet";
import {RequestCommandBase} from "../../Ajax/RequestCommandBase";

export interface CalendarBaseProps<V> {
    options: CalendarOptions;
    viewSettings: V;
}

export interface CalendarBaseState<S> {
    viewState?: S;
    focusedDate: DateTime;
    bookingOptions?: MergedActions;
}

export abstract class CalendarComponentBase<V, S> extends React.Component<CalendarBaseProps<V>,CalendarBaseState<S>> {

    constructor(props) {
        super(props);

        let focusedDate: DateTime = this.props.options.focused_tstamp ? new DateTime(this.props.options.focused_tstamp) : DateTime.create();
        this.state = {
            focusedDate: focusedDate
        }
    }

    componentDidMount() {
        this.updateBookingOptions();
    }

    protected abstract prepareUpdateBookingOptions(calendarReuqest: MergedActionsRequest): MergedActionsRequest;

    protected updateBookingOptions() {
        let calendarRequest = this.prepareUpdateBookingOptions(new MergedActionsRequest());
        calendarRequest.fromDateTime = this.state.focusedDate.getFirstDayThisMonth();
        calendarRequest.toDateTime =  this.state.focusedDate.getLastDayThisMonth();

        console.log(
            "DateTime:",this.state.focusedDate,
            "FirstDay:",this.state.focusedDate.getFirstDayThisMonth(),
            "LastDay:",this.state.focusedDate.getLastDayThisMonth()
        )

        calendarRequest.options = this.props.options;

        let requestSet = new RequestSet(calendarRequest);
        requestSet.send().then((results: { [p: string]: RequestCommandBase<any> }) => {
            this.setState((prevState: CalendarBaseState<S>) => {
                for(const [key, value] of Object.entries(results)) {
                    if(value.getResult() instanceof MergedActions) {
                        prevState.bookingOptions = value.getResult();
                    }
                }
                return prevState;
            });
        });
    }
}