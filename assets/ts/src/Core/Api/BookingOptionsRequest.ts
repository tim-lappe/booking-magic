import {CalendarBookingOptions} from "../Entity/CalendarBookingOptions";
import {HttpRequest} from "./HttpRequest";
import {RequestCommandBase} from "./RequestCommandBase";

export class BookingOptionsRequest extends RequestCommandBase<CalendarBookingOptions> {

    public fromTstamp: number;
    public toTstamp: number;
    public options: any;

    constructor() {
        super();
    }

    public send(): Promise<CalendarBookingOptions> {
        let promise = HttpRequest.PostRequestJson("getBookingOptions", {
            "options": this.options,
            "from_tstamp": this.fromTstamp,
            "to_tstamp": this.toTstamp,
        });

        return new Promise<CalendarBookingOptions>((resolve, reject) => {
            promise.then((data) => {
                try {
                    let bookingOptions = new CalendarBookingOptions(data);
                    resolve(bookingOptions);
                } catch  {
                    reject();
                }
            }).catch(() => reject());
        });
    }
}