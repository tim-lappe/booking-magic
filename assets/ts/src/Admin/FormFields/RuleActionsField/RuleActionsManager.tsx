import {DateSlotItem} from "./DateSlotItem";
import {RuleActionItemBase} from "./RuleActionItemBase";
import {TimeSlotItem} from "./TimeSlotItem";
import {MessageItem} from "./MessageItem";

export class RuleActionItemMeta {
    public name: string;
    public title: string;
    public description: string;
    public category: string;
    public className: typeof RuleActionItemBase;
}

export class RuleActionsManager {

    private ruleActionItems: RuleActionItemMeta[] = [
        {
            name: "date_slot",
            title: "Day slot",
            category: "All Day",
            description: "",
            className: DateSlotItem
        },
        {
            name: "time_slot",
            title: "Time slot",
            description: "",
            category: "Time specific",
            className: TimeSlotItem
        },
        {
            name: "multiple_time_slot",
            description: "",
            title: "Multiple time slots",
            category: "Time specific",
            className: TimeSlotItem
        },
        {
            name: "message",
            title: "Message",
            description: "",
            category: "Miscellous",
            className: MessageItem
        }
    ];

    constructor() {

    }

    public getActionElementsList(): { [props: string]: RuleActionItemMeta[] } {
        let categories = this.getCategories();
        let elements = {};
        for (let cat of categories) {
            elements[cat] = [];
        }
        for (let elem of this.ruleActionItems) {
            elements[elem.category].push(elem);
        }
        return elements;
    }


    public getCategories(): string[] {
        let categories = [];
        for (let actionItem of this.ruleActionItems) {
            if (categories.indexOf(actionItem.category) == -1) {
                categories.push(actionItem.category);
            }
        }
        return categories;
    }
}