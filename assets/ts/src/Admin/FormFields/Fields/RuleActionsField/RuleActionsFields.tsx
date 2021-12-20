import * as React from "react";
import {RuleActionsEntry} from "./RuleActionEntry";

export class RuleActionsFields extends React.Component<any, any> {

    constructor(props) {
        super(props);

        this.onAdd = this.onAdd.bind(this);
        this.onMoveDown = this.onMoveDown.bind(this);
        this.onMoveUp = this.onMoveUp.bind(this);
        this.onRemove = this.onRemove.bind(this);

        try {
            let jsondata = decodeURIComponent(props["data-json"]);
            jsondata = JSON.parse(jsondata);

            if(Array.isArray(jsondata)) {
                for (let i = 0; i < jsondata.length; i++) {
                    jsondata[i].list_id = i.toString();
                }

                this.state = {
                    data: jsondata
                };
            } else {
                this.state = {
                    data: []
                };
            }
        } catch (e: any) {
            this.state = {
                data: []
            };
        }
    }

    onAdd(event: any) {
        let data = this.state.data;
        data.push({
            list_id: Math.random()
        });

        this.setState({
            data: [...data]
        });

        event.preventDefault();
    }

    onMoveDown(index: number) {
        let data = this.state.data;
        [data[index], data[index + 1]] =  [data[index + 1], data[index]];

        this.setState({
            data: [...data]
        });
    }

    onMoveUp(index: number) {
        let data = this.state.data;
        [data[index], data[index - 1]] =  [data[index - 1], data[index]];

        this.setState({
            data: [...data]
        });
    }

    onRemove(index: number) {
        let data = this.state.data;
        data.splice(index, 1);

        this.setState({
            data: [...data]
        });
    }

    render() {
        let arr_data = this.state.data;
        console.log(arr_data);
        let datavalue = encodeURIComponent(JSON.stringify(this.state.data));
        return (
            <div className="tlbm-rule-actions-field-component">
                <input type={"hidden"} value={datavalue}/>
                <div className="tlbm-actions-list">
                    { arr_data.map((item, index) => (
                        <RuleActionsEntry onRemove={() => this.onRemove(index)}
                                          onMoveUp={() => this.onMoveUp(index)}
                                          onMoveDown={() => this.onMoveDown(index)}
                                          dataItem={item} key={item.list_id.toString()} />
                    )) }
                </div>
                <select className="tlbm-action-select-type">
                    <option>Alle</option>
                </select>
                <button onClick={this.onAdd} className="button tlbm-add-action">Add</button>
            </div>
        );
    }
}