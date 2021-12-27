export class RuleAction {
    id: number;
    action_type: string;
    weekdays?: string = "every_day";
    time_hour?: number;
    time_min?: number;
    priority?: number;
    actions?: any;

    constructor() {

    }
}