export class DateTime {

    public date: Date;


    constructor(tstamp: number) {
        this.date = new Date();

        if(tstamp > 0) {
            this.setTime(tstamp);
        }
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
        let time = this.getTime();
        time += 60 * 60 * 24 * days;
        this.setTime(time);
    }

    public getFirstDayThisMonth(): DateTime {
        let firstDay = new DateTime(this.getTime());
        firstDay.setYear(this.getYear(), this.getMonth(), 1);
        return firstDay;
    }

    public getLastDayThisMonth(): DateTime {
        let lastDay = new DateTime(this.getTime());
        lastDay.setYear(this.getYear(), this.getMonth() + 1, 0);
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

    public setYear(year: number, month?: number, date?: number) {
        this.date.setFullYear(year, month - 1, date)
    }

    public getYear(): number {
        return this.date.getFullYear();
    }

    public setTime(time: number) {
        this.date.setTime(time * 1000);
    }

    public getTime(): number {
        return this.date.getTime() / 1000;
    }

    public getMonth(): number {
        return this.date.getMonth() + 1;
    }

    public setMonth(month: number): number {
        return this.date.setMonth(month - 1);
    }

    public static create(): DateTime {
        return new DateTime(DateTime.time());
    }

    public static time(): number {
        return Date.now() / 1000;
    }

    public static getDatesBetween(start: DateTime, end: DateTime): DateTime[] {
        if(start.getTime() < end.getTime()) {
            let dates = [];
            let dateTraverse = new DateTime(start.getTime());
            let endCpy = new DateTime(end.getTime());
            endCpy.setTime(endCpy.getTime() + ((60 * 60 * 24) - 1));

            while(dateTraverse.getTime() < endCpy.getTime()) {
                dates.push(new DateTime(dateTraverse.getTime()));
                dateTraverse.addDays(1);
            }

            return dates;
        }

        return [];
    }
}