import * as React from "react";

export class RuleActionsEntry extends React.Component<any, any> {

    constructor(props) {
        super(props);

        this.state = {
            item: this.props.dataItem
        };

        this.onRemove = this.onRemove.bind(this);
        this.onMoveUp = this.onMoveUp.bind(this);
        this.onMoveDown = this.onMoveDown.bind(this);
    }

    onRemove(event: any) {
        this.props.onRemove(this.props.dataItem);
        event.preventDefault();
    }

    onMoveUp(event: any) {
        this.props.onMoveUp(this.props.dataItem);
        event.preventDefault();
    }

    onMoveDown(event: any) {
        this.props.onMoveDown(this.props.dataItem);
        event.preventDefault();
    }

    render() {
        return (
            <div className={'tlbm-action-rule-item tlbm-gray-container'}>
                <div className={'tlbm-action-item-form'}>
                    {this.state.item.list_id}
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