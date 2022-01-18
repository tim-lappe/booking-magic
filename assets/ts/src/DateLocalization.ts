import {Localization} from "./Localization";

export class DateLocalization {
    public static GetMonthLabelByNum(num: number) {
        let months = Localization.__Arr("months");
        return months[((num + 11) % 12) + 1];
    }

    public static GetWeekdayLabel(slug: string) {
        let weekdays = Localization.__Arr("weekdays");
        return weekdays[slug];
    }

    public static GetWeekdayLabelByNum(num: number) {
        let weekdays = Localization.__Arr("weekdays");
        return Object.entries(weekdays)[num - 1][1];
    }
}