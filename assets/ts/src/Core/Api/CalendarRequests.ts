export class CalendarRequests {

    constructor() {

    }

    public send(): Promise {
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