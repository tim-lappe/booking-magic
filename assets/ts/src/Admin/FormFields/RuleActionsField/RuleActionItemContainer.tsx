import * as React from "react";
import {RuleAction} from "../../Entity/RuleAction";
import {RuleActionsManager} from "./RuleActionsManager";


interface RuleActionsItemProps {
    onChange?: (action: RuleAction) => void;
    onRemove?: (action: RuleAction) => void;
    onMoveUp?: (action: RuleAction) => void;
    onMoveDown?: (action: RuleAction) => void;
    dataItem?: RuleAction;
    ruleActionsManager: RuleActionsManager;
}

interface RuleActionsItemState {
    item: RuleAction;
}

export class RuleActionsItemContainer extends React.Component<RuleActionsItemProps, RuleActionsItemState> {

    private readonly ruleActionComponent: JSX.Element = null;

    constructor(props) {
        super(props);

        this.state = {
            item: props.dataItem
        };

        this.onRemove = this.onRemove.bind(this);
        this.onMoveUp = this.onMoveUp.bind(this);
        this.onMoveDown = this.onMoveDown.bind(this);
        this.onChange = this.onChange.bind(this);

        this.ruleActionComponent = this.props.ruleActionsManager.createComponent(this.state.item, this.onChange)
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
        return (
            <div className={'tlbm-action-rule-item tlbm-gray-container tlbm-admin-content-box'}>
                <div className={'tlbm-action-item-form'}>
                    {this.ruleActionComponent}
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