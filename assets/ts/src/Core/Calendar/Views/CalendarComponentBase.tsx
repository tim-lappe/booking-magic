import * as React from "react";
import {DateTime} from "../../DateTime";
import {HttpRequest} from "../../Api/HttpRequest";
import {CalendarBookingOptions} from "../../Entity/CalendarBookingOptions";
import {CalendarOptions} from "../../Entity/CalendarOptions";

export interface CalendarBaseProps<V> {
    options: CalendarOptions;
    viewSettings: V;
}

export interface CalendarBaseState<S> {
    viewState?: S;
    focusedDate: DateTime;
    bookingOptions?: CalendarBookingOptions;
    lastData?: any;
}

export class CalendarComponentBase<V, S> extends React.Component<CalendarBaseProps<V>,CalendarBaseState<S>> {

    constructor(props) {
        super(props);

        let focusedDate: DateTime = this.props.options.focused_tstamp ? new DateTime(this.props.options.focused_tstamp) : DateTime.create();
        this.state = {
            focusedDate: focusedDate
        }
    }

    componentDidMount() {
        this.sendBookingOptionsRequest();
    }

    protected sendBookingOptionsRequest() {
        HttpRequest.PostRequestJson("getBookingOptions", {
            "options": this.props.options,
            "from_tstamp": this.state.focusedDate.getFirstDayThisMonth().getTime(),
            "to_tstamp": this.state.focusedDate.getLastDayThisMonth().getTime()
        }).then((data) => {
            let bookingOptions = new CalendarBookingOptions(data);
            this.setState((prevState: CalendarBaseState<S>) => {
                prevState.bookingOptions = bookingOptions;
                prevState.lastData = data;
                return prevState;
            });
        });
    }
}