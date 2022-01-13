import {NestedObject} from "./Admin/Entity/NestedObject";

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

    public static decycle(obj, stack = []) {
        if (!obj || typeof obj !== 'object') {
            return obj;
        }

        if (stack.includes(obj)) {
            return null;
        }

        let s = stack.concat([obj]);
        return Array.isArray(obj) ? obj.map(x => Utils.decycle(x, s)) : Object.fromEntries(Object.entries(obj).map(([k, v]) => [k, Utils.decycle(v, s)]));
    }

    public static deepObjectAssign<T extends NestedObject<T>>(object: NestedObject<T>, type: { new(): T }) {
        let newObj = new type();

        if(object != null) {
            Object.assign(newObj, object);

            for (let i = 0; i < object.children.length; i++) {
                newObj.children[i] = this.deepObjectAssign(object.children[i], type);
                newObj.children[i].parent = newObj;
            }
        }

        return newObj;
    }
}