import * as React from "react";
import {RuleActionItemMeta} from "./RuleActionsManager";

interface ActionWindowItemProps {
    actionItem: RuleActionItemMeta;
    onClick: (item: RuleActionItemMeta) => void;
}

interface ActionWindowItemState {

}


export class SelectableActionWindowItem extends React.Component<ActionWindowItemProps, ActionWindowItemState> {

    constructor(props) {
        super(props);
        this.onClick = this.onClick.bind(this);
    }

    onClick(event: any) {
        this.props.onClick(this.props.actionItem);
        event.preventDefault();
    }

    render() {
        return (
            <div onClick={this.onClick} className={"tlbm-element-list-item"}>
                <strong>{this.props.actionItem.title}</strong><br/>
                <div className={"tlbm-elem-description"}>
                    {this.props.actionItem.description}
                </div>
            </div>
        );
    }
}