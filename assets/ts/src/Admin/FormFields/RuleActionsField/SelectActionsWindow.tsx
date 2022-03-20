import * as React from "react";
import {Localization} from "../../../Localization";
import {RuleActionItemMeta, RuleActionsManager} from "./RuleActionsManager";
import {SelectableActionWindowItem} from "./SelectableActionWindowItem";

interface SelectActionsWindowProps {
    onCancel?: () => void;
    onAddAction?: (actionMeta: RuleActionItemMeta) => void;
    ruleActionsManager: RuleActionsManager;
    show: boolean;
}

interface SelectActionsWindowState {
    searchString: string;
}

export class SelectActionsWindow extends React.Component<SelectActionsWindowProps, SelectActionsWindowState> {

    constructor(props) {
        super(props);

        this.onBackgroundClicked = this.onBackgroundClicked.bind(this);
        this.onSearchInputChanged = this.onSearchInputChanged.bind(this);
        this.onActionClicked = this.onActionClicked.bind(this);
        this.onCancelClicked = this.onCancelClicked.bind(this);

        this.state = {
            searchString: ""
        }
    }

    onActionClicked(item: RuleActionItemMeta) {
        if (this.props.onAddAction) {
            this.props.onAddAction(item);
        }
    }

    onSearchInputChanged(event: any) {
        this.setState((prevState: SelectActionsWindowState) => {
            prevState.searchString = event.target.value;
            return prevState;
        });
    }

    onBackgroundClicked(event: any) {
        if (event.target.classList.contains("tlbm-select-action-window")) {
            if (this.props.onCancel != null) {
                this.props.onCancel();
            }
        }

        event.preventDefault();
    }

    onCancelClicked(event: any) {
        if (this.props.onCancel != null) {
            this.props.onCancel();
        }

        event.preventDefault();
    }

    render() {
        let elements = this.props.ruleActionsManager.getActionElementsList();
        let categories = this.props.ruleActionsManager.getCategories();

        let filteredActions = {};
        let filteredCategories = [];

        Object.entries(elements).forEach(([category, value]) => {
            let filteredFormElements = value.filter((actionItem) => {
                let search = this.state.searchString.toLowerCase().replace(" ", "").replace("-", "");
                let title = actionItem.title.toLowerCase().replace(" ", "").replace("-", "");
                let description = actionItem.description.toLowerCase().replace(" ", "").replace("-", "");

                return (title.indexOf(search) != -1 || description.indexOf(search) != -1);
            });
            if (filteredFormElements.length > 0) {
                filteredActions[category] = filteredFormElements;
                filteredCategories.push(category);
            }
        });

        return (
            <div onClick={this.onBackgroundClicked} style={{display: this.props.show ? "flex" : "none"}}
                 className={"tlbm-select-action-window tlbm-window-outer"}>
                <div className={"tlbm-add-action-window-inner tlbm-window-inner"}>
                    <div className={"tlbm-add-action-top-bar"}>
                        <input onChange={this.onSearchInputChanged} className={"regular-text"} type={"text"}
                               placeholder={Localization.__("Search...")}/>
                        <button onClick={this.onCancelClicked}
                                className={"button tlbm-cancel-button"}>{Localization.__("Cancel")}</button>
                    </div>
                    <div className={"tlbm-add-action-list-container"}>
                        {filteredCategories.map((category) => {
                            return (
                                <div key={category} className={"tlbm-action-elements-category"}>
                                    <h3>{category}</h3>
                                    <div className={"tlbm-action-elements-list"}>
                                        {filteredActions[category].map((item) => {
                                            return (
                                                <SelectableActionWindowItem key={item.name}
                                                                            onClick={this.onActionClicked}
                                                                            actionItem={item}/>
                                            )
                                        })}
                                    </div>
                                </div>
                            )
                        })}
                    </div>
                </div>
            </div>
        );
    }
}