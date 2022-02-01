export class DateTime {

    public fullDay: boolean = false;

    public date: Date;

    constructor(year: number = null, month: number = null, day: number = null, hour: number = null, minute: number = null, seconds: number = null) {
        this.date = new Date();

        if(year > 0) {
            this.setYear(year, month, day);
        }
        if(hour != null) {
            this.setHourMin(hour, minute, seconds);
        }
    }

    public toJSON(): any {
        if(this.isFullDay()) {
            return {
                "year": this.getYear(),
                "month": this.getMonth(),
                "day": this.getMonthDay()
            };
        }

        return {
            "year": this.getYear(),
            "month": this.getMonth(),
            "day": this.getMonthDay(),
            "hour": this.getHour(),
            "minute": this.getMinute(),
            "seconds": this.getSeconds()
        };
    }

    public setFullDay(isFullDay: boolean) {
        this.fullDay = isFullDay;
    }

    public isFullDay() {
        return this.fullDay;
    }

    public setHourMin(hours: number, minutes: number, seconds: number = 0) {
        this.date.setHours(hours, minutes, seconds);
    }

    public getHour(): number {
        return this.date.getHours();
    }

    public getMinute(): number {
        return this.date.getMinutes();
    }

    public getSeconds(): number {
        return this.date.getSeconds();
    }

    public addMonth(months: number) {
        let n = this.getMonthDay();
        this.setMonthDay(1);
        this.setMonth(this.getMonth() + months);
        this.setMonthDay(Math.min(n, this.getLastDayThisMonth().getMonthDay()));
    }

    public isDayNow(): boolean {
        let now = DateTime.create();
        return this.getMonthDay() == now.getMonthDay() && this.getMonth() == now.getMonth() && this.getYear() == now.getYear();
    }

    public isMonthNow(): boolean {
        let now = DateTime.create();
        return this.getMonth() == now.getMonth() && this.getYear() == now.getYear();
    }

    public addDays(days: number) {
        this.date.setDate(this.date.getDate() + days);
    }

    public getFirstDayThisMonth(): DateTime {
        let firstDay = new DateTime();
        firstDay.setYear(this.getYear(), this.getMonth(), 1);
        firstDay.setHourMin(0, 0, 1);
        firstDay.setFullDay(true);
        return firstDay;
    }

    public getLastDayThisMonth(): DateTime {
        let lastDay = new DateTime();
        lastDay.setYear(this.getYear(), (this.getMonth() + 1), 0);
        lastDay.setHourMin(23, 59, 59);
        lastDay.setFullDay(true);
        return lastDay;
    }

    public getWeekday(): number {
        return ((this.date.getDay() + 6) % 7) + 1;
    }

    public getMonthDay(): number {
        return this.date.getDate();
    }

    public setMonthDay(day: number) {
        this.date.setDate(day);
    }

    public setYear(year: number, month: number = null, date: number = null) {
        if(month != null && date != null) {
            this.date.setFullYear(year, month - 1, date);
        } else if(month != null) {
            this.date.setFullYear(year, month - 1);
        } else {
            this.date.setFullYear(year);
        }
    }

    public getYear(): number {
        return this.date.getFullYear();
    }

    public getMonth(): number {
        return this.date.getMonth() + 1;
    }

    public setMonth(month: number): number {
        return this.date.setMonth(month - 1);
    }

    public getDaysAsDateTimesInMonth(): DateTime[] {
        let dateTimes: DateTime[] = [];
        let firstDay = this.getFirstDayThisMonth().getMonthDay();
        let lastDay = this.getLastDayThisMonth().getMonthDay();

        for(let d = firstDay; d <= lastDay; d++) {
            let dt = new DateTime(this.getYear(), this.getMonth(), d, this.getHour(), this.getMinute(), this.getSeconds());
            dateTimes.push(dt);
        }

        return dateTimes;
    }


    public static create(): DateTime {
        return new DateTime();
    }

    public static time(): number {
        return Date.now() / 1000;
    }

    public static fromObj(obj: any): DateTime {
        return new DateTime(obj.year, obj.month, obj.day, obj.hour, obj.minute, obj.seconds);
    }

    public static copy(dateTime: DateTime) {
        return new DateTime(
            dateTime.getYear(),
            dateTime.getMonth(),
            dateTime.getMonthDay(),
            dateTime.getHour(),
            dateTime.getMinute(),
            dateTime.getSeconds()
        )
    }

    public static isSameMinute(...dateTimes: DateTime[]) {
        if(dateTimes.length >= 2) {
            let prev: DateTime = dateTimes[0];
            dateTimes.splice(0, 1);
            for (let dateTime of dateTimes) {
                if (prev.getMonthDay() != dateTime.getMonthDay() ||
                    prev.getMonth() != dateTime.getMonth() ||
                    prev.getYear() != dateTime.getYear() ||
                    prev.getHour() != dateTime.getHour() ||
                    prev.getMinute() != dateTime.getMinute()) {

                    return false;
                }
                prev = dateTime;
            }
        }
        return true;
    }

    public static isSameDay(...dateTimes: DateTime[]) {
        if(dateTimes.length >= 2) {
            dateTimes = dateTimes.filter(dt => dt);
            let prev: DateTime = dateTimes[0];
            dateTimes.splice(0, 1);

            for (let dateTime of dateTimes) {
                if (prev.getMonthDay() != dateTime.getMonthDay() ||
                    prev.getMonth() != dateTime.getMonth() ||
                    prev.getYear() != dateTime.getYear()) {

                    return false;
                }
                prev = dateTime;
            }
        }
        return true;
    }
}
