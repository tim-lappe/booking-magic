import * as React from "react";
import {DateTime} from "../../Adapter/DateTime";
import {HttpRequest} from "../../Api/HttpRequest";
import {CalendarBookingOptions} from "../../Entity/CalendarBookingOptions";
import {CalendarOptions} from "../../Entity/CalendarOptions";
import {BookingOptionsRequest} from "../../Api/BookingOptionsRequest";

export interface CalendarBaseProps<V> {
    options: CalendarOptions;
    viewSettings: V;
}

export interface CalendarBaseState<S> {
    viewState?: S;
    focusedDate: DateTime;
    bookingOptions?: CalendarBookingOptions;
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

    protected abstract prepareUpdateBookingOptions(calendarReuqest: BookingOptionsRequest): BookingOptionsRequest;

    protected updateBookingOptions() {
        let calendarRequest = this.prepareUpdateBookingOptions(new BookingOptionsRequest());
        calendarRequest.fromTstamp = this.state.focusedDate.getFirstDayThisMonth().getTime();
        calendarRequest.toTstamp =  this.state.focusedDate.getLastDayThisMonth().getTime();
        calendarRequest.options = this.props.options;

        calendarRequest.send().then((data) => {
            this.setState((prevState: CalendarBaseState<S>) => {
                prevState.bookingOptions = data;
                return prevState;
            });
        });
    }
}