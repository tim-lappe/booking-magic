import * as React from "react";
import {RuleAction} from "../../Entity/RuleAction";
import {TimeSlotItem} from "./TimeSlotItem";
import {DateSlotItem} from "./DateSlotItem";
import {MessageItem} from "./MessageItem";


interface RuleActionsItemProps {
    onChange?: (action: RuleAction) => void;
    onRemove?: (action: RuleAction) => void;
    onMoveUp?: (action: RuleAction) => void;
    onMoveDown?: (action: RuleAction) => void;
    dataItem?: RuleAction;
}

interface RuleActionsItemState {
    item: RuleAction;
}

export class RuleActionsItemContainer extends React.Component<RuleActionsItemProps, RuleActionsItemState> {

    constructor(props) {
        super(props);

        this.state = {
            item: props.dataItem
        };

        if (props.dataItem.action_type == "message") {
            this.state.item.actions = {
                "message": props.dataItem?.actions?.message ?? ""
            }
        }

        this.onRemove = this.onRemove.bind(this);
        this.onMoveUp = this.onMoveUp.bind(this);
        this.onMoveDown = this.onMoveDown.bind(this);
        this.onChange = this.onChange.bind(this);

    }

    onRemove(event: any) {
        this.props.onRemove(this.state.item);
        event.preventDefault();
    }

    onMoveUp(event: any) {
        this.props.onMoveUp(this.state.item);
        event.preventDefault();
    }

    onMoveDown(event: any) {
        this.props.onMoveDown(this.state.item);
        event.preventDefault();
    }

    onChange(item: RuleAction) {
        this.props.onChange(item);
    }

    componentDidMount() {
        this.onChange(this.state.item);
    }

    render() {
        let formcontent = (<div />);

        if(this.state.item.action_type == "time_slot") {
            formcontent = (<TimeSlotItem ruleAction={this.state.item} onChange={this.onChange}/>)
        }

        if(this.state.item.action_type == "date_slot") {
            formcontent = (<DateSlotItem ruleAction={this.state.item} onChange={this.onChange}/>)
        }

        if(this.state.item.action_type == "message") {
            formcontent = (<MessageItem ruleAction={this.state.item} onChange={this.onChange}/>)
        }

        return (
            <div className={'tlbm-action-rule-item tlbm-gray-container tlbm-admin-content-box'}>
                <div className={'tlbm-action-item-form'}>
                    {formcontent}
                </div>
                <div className={'tlbm-up-down-buttons'}>
                    <button onClick={this.onMoveUp} className={'tlbm-ud-button-up'}><span className={'dashicons dashicons-arrow-up-alt2'} /></button>
                    <button onClick={this.onMoveDown} className={'tlbm-ud-button-down'}><span className={'dashicons dashicons-arrow-down-alt2'} /></button>
                </div>
                <button onClick={this.onRemove} className={'button button-small tlbm-action-item-delete'}><span className={'dashicons dashicons-trash'} /></button>
            </div>
        );
    }
}