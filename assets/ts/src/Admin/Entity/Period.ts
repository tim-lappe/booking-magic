import {PeriodTimeRange} from "./PeriodTimeRange";
import {DateTime} from "../../Core/Adapter/DateTime";

export class Period {

    public id: number = 0;

    public fromDateTime: DateTime = null;

    public fromTimeset: boolean = false;

    public toDateTime: DateTime = null;

    public toTimeset: boolean = false;

    public dailyTimeRanges: PeriodTimeRange[] = [];

    constructor() {
        this.fromDateTime = DateTime.create();
        this.toDateTime = DateTime.create();
    }

    public toJSON() {
        return {
            id: this.id,
            fromDateTime: this.fromDateTime,
            fromTimeset: this.fromTimeset,
            toDateTime: this.toDateTime,
            toTimeset: this.toTimeset,
            dailyTimeRanges: this.dailyTimeRanges
        }
    }

    public static fromData(obj: any) {
        let period = new Period();
        Object.assign(period, obj);

        if(obj['fromDateTime'] && obj['fromDateTime']['year'] > 0) {
            period.fromDateTime = new DateTime();
            period.fromDateTime.setYear(
                obj['fromDateTime']['year'],
                obj['fromDateTime']['month'],
                obj['fromDateTime']['day']);

            period.fromDateTime.setHourMin(
                obj['fromDateTime']['hour'],
                obj['fromDateTime']['minute'],
                obj['fromDateTime']['seconds']);
        } else {
            period.fromDateTime = new DateTime();
        }

        if(obj['toDateTime'] && obj['toDateTime']['year'] > 0) {
            period.toDateTime = new DateTime();
            period.toDateTime.setYear(
                obj['toDateTime']['year'],
                obj['toDateTime']['month'],
                obj['toDateTime']['day']);

            period.toDateTime.setHourMin(
                obj['toDateTime']['hour'],
                obj['toDateTime']['minute'],
                obj['toDateTime']['seconds']);
        } else {
            period.toDateTime = null;
        }
        return period;
    }
}