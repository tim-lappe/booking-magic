import React = require("react");
import {ItemContentState, RuleActionItemBase} from "./RuleActionItemBase";
import {Localization} from "../../../../Localization";

export class MessageItem extends RuleActionItemBase {

    constructor(props) {
        super(props);

        this.state.ruleAction.actions = {
            "message": props.dataItem?.actions?.message ?? ""
        };

        this.onChangeMessage = this.onChangeMessage.bind(this);
    }

    onChangeMessage(event: any) {
        this.setState((prevState: ItemContentState) => {
            prevState.ruleAction.actions = {
                message: event.target.value
            };

            this.props.onChange(prevState.ruleAction);
            return prevState;
        });

        event.preventDefault();
    }

    protected getFields(): JSX.Element {
        return (
            <React.Fragment>
                <div style={{marginLeft: "20px", flexGrow: 1}}>
                    <small>{Localization.__("Message")}</small><br/>
                    <textarea style={{minWidth: "75%"}} onChange={this.onChangeMessage}
                              value={this.state.ruleAction.actions.message}/>
                </div>
            </React.Fragment>
        );
    }
}