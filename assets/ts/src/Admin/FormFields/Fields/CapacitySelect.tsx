import * as React from "react";
import {Localization} from "../../../Localization";

interface CapacitySelectProps {
    onChange?: (newCapacity: CapacitySelectCapacity) => void;
    modeName?: string;
    amountName?: string;
}

export interface CapacitySelectCapacity {
    mode?: string;
    amount?: number;
}

export class CapacitySelect extends React.Component<CapacitySelectProps, CapacitySelectCapacity> {

    public selectModeDom = React.createRef<HTMLSelectElement>();
    public inputAmountDom = React.createRef<HTMLInputElement>();

    constructor(props: any) {
        super(props);

        this.state = {
            mode: "set",
            amount: 0
        }

        this.onChangeMode = this.onChangeMode.bind(this);
        this.onChangeAmount = this.onChangeAmount.bind(this);
    }

    componentDidMount() {
        this.setState((prevState: CapacitySelectCapacity) => {
            prevState.mode = this.selectModeDom.current.value;
            prevState.amount = parseInt(this.inputAmountDom.current.value);
            this.props.onChange(prevState);
            return prevState;
        });
    }

    onChangeMode(event: any) {
        this.setState((prevState: CapacitySelectCapacity) => {
            prevState.mode = event.target.value.toString();
            this.props.onChange(prevState);

            return prevState;
        });

        event.preventDefault();
    }

    onChangeAmount(event: any) {
        this.setState((prevState: CapacitySelectCapacity) => {
            prevState.amount = event.target.value;
            this.props.onChange(prevState);

            return prevState;
        });
    }

    render() {
        return (
            <div style={{"display": "flex"}}>
                <select ref={this.selectModeDom} onLoad={this.onChangeMode} onChange={this.onChangeMode} value={this.state.mode} name={this.props.modeName ?? "capacity_mode"}>
                    <option value={"set"}>{Localization.__("Set")}</option>
                    <option value={"add"}>{Localization.__("Add")}</option>
                    <option value={"subtract"}>{Localization.__("Subtract")}</option>
                </select>
                <input ref={this.inputAmountDom} onLoad={this.onChangeAmount} onChange={this.onChangeAmount} value={this.state.amount} type="number" min="0" name={this.props.amountName ?? "capacity_amount"} />
            </div>
        )
    }
}