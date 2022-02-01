import {MergedActions} from "../Entity/MergedActions";
import {RequestCommandBase} from "./RequestCommandBase";
import {DateTime} from "../Adapter/DateTime";

export class MergedActionsRequest extends RequestCommandBase<MergedActions> {

    public fromDateTime: DateTime;
    public toDateTime: DateTime;
    public options: any;

    /**
     *
     * @private
     */
    private result: MergedActions = null;

    constructor() {
        super();
    }

    public getPayload(): any {
        return {
            "options": this.options,
            "fromDateTime": this.fromDateTime,
            "toDateTime": this.toDateTime,
        };
    }

    public getAction(): string {
        return "getBookingOptions";
    }

    public setResult(data: any) {
        this.result = new MergedActions(data);
    }

    public getResult(): MergedActions {
        return this.result;
    }
}