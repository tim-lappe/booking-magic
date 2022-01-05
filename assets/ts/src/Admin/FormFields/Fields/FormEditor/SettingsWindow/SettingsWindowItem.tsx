import * as React from "react";
import {FormData} from "../../../../Entity/FormData";
import {FormElementSettingsType} from "../../../../Entity/FormElementSettingsType";


interface SettingsWindowItemProps {
    formData: FormData;
    settingsType: FormElementSettingsType;
}

interface SettingsWindowItemState {

}

export class SettingsWindowItem extends React.Component<SettingsWindowItemProps, SettingsWindowItemState> {

    constructor(props) {
        super(props);
    }

    render() {
        let val = this.props.formData[this.props.settingsType.name] ?? "Beispielwert";

        return (
            <div className={"form-item-settings-control"}>
                <label>
                    {this.props.settingsType.title}<br />
                    <input name={this.props.settingsType.name} type={"text"} value={val} />
                </label>
            </div>
        );
    }
}