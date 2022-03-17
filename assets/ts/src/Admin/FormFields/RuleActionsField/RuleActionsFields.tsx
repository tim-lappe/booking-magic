import * as React from "react";
import {RuleActionsItemContainer} from "./RuleActionItemContainer";
import {Localization} from "../../../Localization";
import {RuleAction} from "../../Entity/RuleAction";
import {Utils} from "../../../Utils";

interface RuleActionsFieldsState {
    items: RuleAction[];
    select_type: string;
}

export class RuleActionsFields extends React.Component<any, RuleActionsFieldsState> {

    constructor(props) {
        super(props);

        this.onAdd = this.onAdd.bind(this);
        this.onMoveDown = this.onMoveDown.bind(this);
        this.onMoveUp = this.onMoveUp.bind(this);
        this.onRemove = this.onRemove.bind(this);
        this.onChange = this.onChange.bind(this);
        this.onSelectTypeChanged = this.onSelectTypeChanged.bind(this);

        let jsondata = Utils.decodeUriComponent(props.dataset.json);
        jsondata = JSON.parse(jsondata);

        this.state = {
            items: [],
            select_type: "date_slot"
        }

        if(Array.isArray(jsondata)) {
            this.state = {
                items: jsondata,
                select_type: "date_slot"
            }
        }
    }

    onAdd(event: any) {
        let items = this.state.items;
        let dataItem = new RuleAction();
        dataItem.id = -Math.random();
        dataItem.action_type = this.state.select_type;

        this.setState({
            items: [...items, dataItem]
        });

        event.preventDefault();
    }

    onMoveDown(index: number) {
        let items = this.state.items;
        [items[index], items[index + 1]] =  [items[index + 1], items[index]];

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
            select_type: event.target.value
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
                <input name={this.props.dataset.name} type={"hidden"} value={datavalue}/>
                <div className="tlbm-actions-list">
                    {this.state.items.map((item, index) => {
                        return <RuleActionsItemContainer
                            onRemove={() => this.onRemove(index)}
                            onChange={() => this.onChange(item, index)}
                            onMoveUp={() => this.onMoveUp(index)}
                            onMoveDown={() => this.onMoveDown(index)}
                            dataItem={item} key={item.id}
                        />
                    })}
                </div>
                <select onChange={this.onSelectTypeChanged} className="tlbm-action-select-type"
                        value={this.state.select_type}>
                    <optgroup label={Localization.__("All Day")}>
                        <option value={'date_slot'}>{Localization.__("Day slot")}</option>
                    </optgroup>
                    <optgroup label={Localization.__("Time specific")}>
                        <option value={'time_slot'}>{Localization.__("Time slot")}</option>
                        <option value={'multiple_time_slots'}>{Localization.__("Multiple time slots")}</option>
                    </optgroup>
                    <optgroup label={Localization.__("Miscellous")}>
                        <option value={'message'}>{Localization.__("Message")}</option>
                    </optgroup>
                </select>
                <button onClick={this.onAdd} className="button tlbm-add-action">{Localization.__("Add")}</button>
            </div>
        );
    }
}