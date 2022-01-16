import * as React from "react";
import {DateTime} from "../../DateTime";

export interface CalendarBaseProps<V> {
    options: any;
    viewSettings: V;
}

export interface CalendarBaseState<S> {
    viewState?: S;
    focusedDate: DateTime;
}

export class CalendarComponentBase<V, S> extends React.Component<CalendarBaseProps<V>,CalendarBaseState<S>> {

    constructor(props) {
        super(props);

        let focusedDate: DateTime = this.props.options.focused_tstamp ? new DateTime(this.props.options.focused_tstamp) : DateTime.create();
        this.state = {
            focusedDate: focusedDate
        }
    }
}