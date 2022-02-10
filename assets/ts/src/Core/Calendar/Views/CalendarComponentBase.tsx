import * as React from "react";
import {DateTime} from "../../Adapter/DateTime";
import {MergedActions} from "../../Entity/MergedActions";
import {CalendarDisplay} from "../../Entity/CalendarDisplay";
import {MergedActionsRequest} from "../../Ajax/MergedActionsRequest";
import {RequestSet} from "../../Ajax/RequestSet";
import {RequestCommandBase} from "../../Ajax/RequestCommandBase";

export interface CalendarBaseProps {
    display: CalendarDisplay;
    name?: string;
}

export interface CalendarBaseState<S> {
    viewState?: S;
    focusedDate: DateTime;
    bookingOptions?: MergedActions;
    formValue: any;
}

export abstract class CalendarComponentBase<S> extends React.Component<CalendarBaseProps,CalendarBaseState<S>> {

    constructor(props) {
        super(props);

        let focusedDate: DateTime = DateTime.create();
        this.state = {
            focusedDate: focusedDate,
            formValue: null
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
        calendarRequest.display = this.props.display;

        let requestSet = new RequestSet(calendarRequest);

        requestSet.send().then((results: { [p: string]: RequestCommandBase<any> }) => {
            this.setState((prevState: CalendarBaseState<S>) => {
                for(const [, value] of Object.entries(results)) {
                    if(value.getResult() instanceof MergedActions) {
                        prevState.bookingOptions = value.getResult();
                    }
                }
                return prevState;
            });
        });
    }

    /**
     *
     * @protected
     */
    protected getEncodedValue(): string {
        return encodeURIComponent(JSON.stringify(this.state.formValue));
    }
}