import * as React from "react";
import {Utils} from "../../Utils";


export class CalendarCategoryField extends React.Component<any, any> {

    constructor(props) {
        super(props);

        this.onCategoryCanged = this.onCategoryCanged.bind(this);

        let datavalue = JSON.parse(Utils.decodeUriComponent(this.props.dataset.value));
        let categories = JSON.parse(Utils.decodeUriComponent(this.props.dataset.categories));
        let name = props.dataset.name;

        let state = {
            value: [],
            categories: [],
            name: name
        }

        if (Array.isArray(categories)) {
            state.categories = categories;
        }

        if (Array.isArray(datavalue)) {
            state.value = datavalue;
        }

        this.state = state;
    }

    onCategoryCanged(event: any) {
        this.setState((prevState) => {
            let elem = event.target as HTMLInputElement;
            let categoryId = Number.parseInt(elem.value);
            if(elem.checked) {
                prevState.value.push(categoryId);
            } else {
                prevState.value = prevState.value.filter((value) => value !== categoryId);
            }

            return prevState;
        });
    }

    render() {
        return (
            <React.Fragment>
                <input type={"hidden"} name={this.state.name} value={encodeURIComponent(JSON.stringify(this.state.value))} />
                <div className={"tlbm-calendar-categories"} style={{display: this.state.categories.length > 0 ? "flex": "none"}}>
                    <div className={"tlbm-gray-container tlbm-admin-content-box tlbm-calendar-category-select-item-list"}>
                        {this.state.categories.map((category) => {
                            return (
                                <div key={category.id} className="tlbm-calendar-category-select-item">
                                    <label>
                                        <input type={"checkbox"} value={category.id} onChange={this.onCategoryCanged} checked={this.state.value.includes(category.id)}/>
                                        {category.title}
                                    </label>
                                </div>
                            )
                        })}
                    </div>
                </div>
            </React.Fragment>
        );
    }
}