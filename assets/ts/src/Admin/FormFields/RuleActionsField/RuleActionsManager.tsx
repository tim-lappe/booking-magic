import {RuleActionItemBase} from "./Fields/RuleActionItemBase";
import {RuleAction} from "../../Entity/RuleAction";
import React = require("react");

export class RuleActionItemMeta {
    public name: string;
    public title: string;
    public description: string;
    public category: string;
}

export class RuleActionsManager {

    private ruleActionItemsMeta: RuleActionItemMeta[] = [];
    private ruleActionComponent: { [props: string]: typeof RuleActionItemBase } = {}

    public registerComponent(name: string, componentClass: typeof RuleActionItemBase) {
        this.ruleActionComponent[name] = componentClass;
    }

    public createComponent(ruleAction: RuleAction, onChange: (ruleAction: RuleAction) => void) {
        if (this.ruleActionComponent[ruleAction.action_type]) {
            const ElementComponent = this.ruleActionComponent[ruleAction.action_type];
            return (<ElementComponent ruleAction={ruleAction} onChange={onChange}/>)
        }

        return null;
    }

    public setActionsMeta(meta: RuleActionItemMeta[]) {
        this.ruleActionItemsMeta = meta;
    }

    public getActionElementsList(): { [props: string]: RuleActionItemMeta[] } {
        let categories = this.getCategories();
        let elements = {};
        for (let cat of categories) {
            elements[cat] = [];
        }
        for (let elem of this.ruleActionItemsMeta) {
            elements[elem.category].push(elem);
        }
        return elements;
    }


    public getCategories(): string[] {
        let categories = [];
        for (let actionItem of this.ruleActionItemsMeta) {
            if (categories.indexOf(actionItem.category) == -1) {
                categories.push(actionItem.category);
            }
        }
        return categories;
    }
}