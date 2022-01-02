export class Utils {
    public static decodeUriComponent(str: string): string {
        return decodeURIComponent(str.replace(/\+/g, '%20'))
    }

    public static getUnixTimestamp(val: any = null): number {
        if(val == null) {
            return Math.floor(Date.now() / 1000);
        } else {
            if(val instanceof Date) {
                return Math.floor(val.getTime() / 1000);
            } else if(Number.isInteger(val)) {
                return Math.floor(val / 1000);
            }
        }
    }
}