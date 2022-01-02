import * as React from "react";
import {Localization} from "../../../Localization";
import {Utils} from "../../../Utils";
import {Period} from "../../Entity/Period";
import {DateSelect} from "./DateSelect";
import {PeriodSelectItem} from "./PeriodSelectItem";

interface PeriodSelectState {
    items: Period[];
}

export class PeriodSelect extends React.Component<any, PeriodSelectState>{

    constructor(props) {
        super(props);

        this.onAdd = this.onAdd.bind(this);
        this.onChangeItem = this.onChangeItem.bind(this);

        let jsondata = Utils.decodeUriComponent(props.dataset.json);
        jsondata = JSON.parse(jsondata);

        this.state = {
            items: []
        }

        if(Array.isArray(jsondata)) {
            this.state = {
                items: jsondata
            }
        }
    }

    onAdd(event: any) {
        let items = this.state.items;
        let dataItem = new Period();
        dataItem.id = -Math.random();

        this.setState({
            items: [...items, dataItem]
        });

        event.preventDefault();
    }

    onChangeItem(item: Period) {
        console.log(item);
    }

    render() {
        return (
            <div className="tlbm-period-select-container">
                <input type={"hidden"} name={this.props.dataset.name} value={encodeURIComponent(JSON.stringify(this.state.items))} />
                <div className="tlbm-periods-rules-list">
                    {this.state.items.map((item) => {
                        return (
                            <PeriodSelectItem onChange={this.onChangeItem} item={item} key={item.id} />
                        )
                    })}
                </div>
                <button className="button tlbm-add-period" onClick={this.onAdd}>{Localization.__("Add")}</button>
            </div>
        );
    }
}