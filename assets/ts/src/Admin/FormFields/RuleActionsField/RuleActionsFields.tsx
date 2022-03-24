import * as React from "react";
import {RuleActionsItemContainer} from "./RuleActionItemContainer";
import {Localization} from "../../../Localization";
import {RuleAction} from "../../Entity/RuleAction";
import {Utils} from "../../../Utils";
import {SelectActionsWindow} from "./SelectActionsWindow";
import {RuleActionItemMeta, RuleActionsManager} from "./RuleActionsManager";
import {DaySlotItem} from "./Fields/DaySlotItem";
import {TimeSlotItem} from "./Fields/TimeSlotItem";
import {MessageItem} from "./Fields/MessageItem";
import {MultipleTimeSlotItem} from "./Fields/MultipleTimeSlotItem";
import {SlotOverwriteItem} from "./Fields/SlotOverwriteItem";

interface RuleActionsFieldsState {
    items: RuleAction[];
    selectType: string;
    addActionWindowOpen: boolean;
}

export class RuleActionsFields extends React.Component<any, RuleActionsFieldsState> {

    private ruleActionsManager: RuleActionsManager = new RuleActionsManager();

    constructor(props) {
        super(props);

        this.onAdd = this.onAdd.bind(this);
        this.onMoveDown = this.onMoveDown.bind(this);
        this.onMoveUp = this.onMoveUp.bind(this);
        this.onRemove = this.onRemove.bind(this);
        this.onChange = this.onChange.bind(this);
        this.onAddAction = this.onAddAction.bind(this);
        this.onSelectTypeChanged = this.onSelectTypeChanged.bind(this);
        this.onCancelAddAction = this.onCancelAddAction.bind(this);

        let datavalue = JSON.parse(Utils.decodeUriComponent(props.dataset.value));

        this.ruleActionsManager.setActionsMeta(JSON.parse(Utils.decodeUriComponent(props.dataset.actions)));
        this.ruleActionsManager.registerComponent("day_slot", DaySlotItem);
        this.ruleActionsManager.registerComponent("time_slot", TimeSlotItem);
        this.ruleActionsManager.registerComponent("message", MessageItem);
        this.ruleActionsManager.registerComponent("multiple_time_slots", MultipleTimeSlotItem);
        this.ruleActionsManager.registerComponent("slot_overwrite", SlotOverwriteItem);


        this.state = {
            items: [],
            selectType: "day_slot",
            addActionWindowOpen: false
        }

        if (Array.isArray(datavalue)) {
            this.state = {
                items: datavalue,
                selectType: "day_slot",
                addActionWindowOpen: false
            }
        }
    }

    onAdd(event: any) {
        this.setState({
            addActionWindowOpen: true
        });

        event.preventDefault();
    }

    onAddAction(actionMeta: RuleActionItemMeta) {
        let items = this.state.items;
        let dataItem = new RuleAction();
        dataItem.id = -Math.random();
        dataItem.action_type = actionMeta.name;

        this.setState({
            items: [...items, dataItem],
            addActionWindowOpen: false
        });
    }

    onCancelAddAction() {
        this.setState({
            addActionWindowOpen: false
        });
    }

    onMoveDown(index: number) {
        let items = this.state.items;
        [items[index], items[index + 1]] = [items[index + 1], items[index]];

        this.setState({
            items: [...items]
        });
    }

    onMoveUp(index: number) {
        let items = this.state.items;
        [items[index], items[index - 1]] =  [items[index - 1], items[index]];

        this.setState({
            items: [...items]
        });
    }

    onRemove(index: number) {
        let items = this.state.items;
        items.splice(index, 1);

        this.setState({
            items: [...items]
        });
    }

    onSelectTypeChanged(event: any) {
        this.setState({
            selectType: event.target.value
        })
    }

    onChange(item: RuleAction, index: number) {
        let items = this.state.items;
        items[index] = item;

        this.setState({
            items: [...items]
        });
    }

    render() {
        let datavalue = encodeURIComponent(JSON.stringify(this.state.items));

        return (
            <div className="tlbm-rule-actions-field-component">
                <SelectActionsWindow onCancel={this.onCancelAddAction} ruleActionsManager={this.ruleActionsManager}
                                     onAddAction={this.onAddAction} show={this.state.addActionWindowOpen}/>
                <input name={this.props.dataset.name} type={"hidden"} value={datavalue}/>
                <div className="tlbm-actions-list">
                    {this.state.items.map((item, index) => {
                        return <RuleActionsItemContainer
                            onRemove={() => this.onRemove(index)}
                            onChange={() => this.onChange(item, index)}
                            onMoveUp={() => this.onMoveUp(index)}
                            onMoveDown={() => this.onMoveDown(index)}
                            dataItem={item} key={item.id} ruleActionsManager={this.ruleActionsManager}
                        />
                    })}
                </div>
                <button onClick={this.onAdd}
                        className="button tlbm-add-action">{Localization.getText("Add Action")}</button>
            </div>
        );
    }
}