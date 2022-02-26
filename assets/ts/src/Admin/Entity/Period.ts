import {PeriodTimeRange} from "./PeriodTimeRange";
import {DateTime} from "../../Core/Adapter/DateTime";

export class Period {

    public id: number = 0;

    public fromDateTime: DateTime = null;

    public toDateTime: DateTime = null;

    public dailyTimeRanges: PeriodTimeRange[] = [];

    constructor() {
        this.fromDateTime = DateTime.create();
        this.toDateTime = DateTime.create();
    }

    public toJSON() {
        return {
            id: this.id,
            fromDateTime: this.fromDateTime,
            toDateTime: this.toDateTime,
            dailyTimeRanges: this.dailyTimeRanges
        }
    }

    public static fromData(obj: any) {
        let period = new Period();
        Object.assign(period, obj);

        if(obj['fromDateTime'] && obj['fromDateTime']['year'] > 0) {
            period.fromDateTime = DateTime.fromObj(obj['fromDateTime']);

        } else {
            period.fromDateTime = new DateTime();
        }

        if(obj['toDateTime'] && obj['toDateTime']['year'] > 0) {
            period.toDateTime = DateTime.fromObj(obj['toDateTime']);
        } else {
            period.toDateTime = null;
        }
        return period;
    }
}