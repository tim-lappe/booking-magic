import * as React from "react";
import ReactDOM = require("react-dom");
import {LineChart} from "./LineChart";

export class Charts {

    public static attachLineCharts() {
        document.querySelectorAll(".tlbm-admin-line-chart").forEach(( htmlelement: HTMLElement) => {
            ReactDOM.render(<LineChart dataset={htmlelement.dataset} />, htmlelement);
        });
    }
}