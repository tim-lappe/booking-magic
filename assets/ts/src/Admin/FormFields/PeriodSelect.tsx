import * as React from "react";
import {Localization} from "../../Localization";
import {Utils} from "../../Utils";
import {Period} from "../Entity/Period";
import {PeriodSelectItem} from "./PeriodSelectItem";

interface PeriodSelectState {
    items: Period[];
}

export class PeriodSelect extends React.Component<any, PeriodSelectState>{

    constructor(props) {
        super(props);

        this.onAdd = this.onAdd.bind(this);
        this.onChangeItem = this.onChangeItem.bind(this);
        this.onRemoveItem = this.onRemoveItem.bind(this);

        let jsondata = Utils.decodeUriComponent(props.dataset.json);
        jsondata = JSON.parse(jsondata);

        this.state = {
            items: []
        }

        console.log(jsondata);

        if(Array.isArray(jsondata)) {
            let periods: Period[] = [];
            for (let data of jsondata) {
                periods.push(Period.fromData(data));
            }

            this.state = {
                items: periods
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

    onChangeItem(index: number, item: Period) {
        this.setState((prevState: PeriodSelectState) => {
            prevState.items[index] = item;
            return prevState;
        });
    }

    onRemoveItem(index: number) {
        let items = this.state.items;
        items.splice(index, 1);

        this.setState({
            items: [...items]
        });
    }

    render() {
        return (
            <div className="tlbm-period-select-container">
                <input type={"hidden"} name={this.props.dataset.name} value={encodeURIComponent(JSON.stringify(this.state.items))} />
                <div className="tlbm-periods-rules-list">
                    {this.state.items.map((item, index) => {
                        return (
                            <PeriodSelectItem onRemove={() => this.onRemoveItem(index)} onChange={item1 => this.onChangeItem(index, item1)} item={item} key={item.id} />
                        )
                    })}
                </div>
                <button className="button tlbm-add-period" onClick={this.onAdd}>{Localization.__("Add")}</button>
            </div>
        );
    }
}