import {BasicSettingsTypeElement} from "./BasicSettingsTypeElement";
import * as React from "react";
import {SelectElementSetting} from "../../../../Entity/FormEditor/SelectElementSetting";
import {Localization} from "../../../../../Localization";

export class SelectSettingsType extends BasicSettingsTypeElement {
    render(): JSX.Element {
        let settings = this.props.elementSetting as SelectElementSetting;

        return (
            <label>
                {this.props.elementSetting.title}<br />
                <select disabled={this.props.elementSetting.readonly ?? false} onChange={this.onChange} value={this.state.value}>
                    <option disabled={true} value={""}>
                        {Localization.getText("Nothing selected")}
                    </option>
                    {Object.entries(settings.key_values).map((item: any) => {
                        if (!((typeof item[1] == "string") || (typeof item[1] == "number"))) {
                            return (
                                <optgroup key={item[0]} label={item[0]}>
                                    {item[1] != null ? (
                                        <React.Fragment>
                                            {Object.entries(item[1]).map((subitem: any) => {
                                                return (
                                                    <option key={subitem[0]} value={subitem[0]}>{subitem[1]}</option>
                                                );
                                            })}
                                        </React.Fragment>
                                    ): null}
                                </optgroup>
                            );
                        } else {
                            return (
                                <option key={item[0]} value={item[0]}>{item[1]}</option>
                            )
                        }
                    })}
                </select>
            </label>
        );
    }
}