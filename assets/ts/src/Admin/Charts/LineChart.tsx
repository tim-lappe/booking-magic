import * as React from "react";
import {Line} from "react-chartjs-2";
import {CategoryScale} from 'chart.js';
import Chart from "chart.js/auto";
import {Utils} from "../../Utils";

export class LineChart extends React.Component<any, any> {

    private options = {
        responsive: true,
        aspectRatio: 5,
        scales: {
            yAxis: {
                suggestedMin: 1,
                ticks: {
                    beginAtZero: true,
                    callback: function (value) {
                        if (Number.isInteger(value)) {
                            return value;
                        }
                    },
                    stepSize: 1
                }
            }
        },

        plugins: {
            legend: {
                position: 'top' as const,
                display: false
            },
            title: {
                display: false,
                text: 'Chart.js Line Chart',
            },
        },
    };


    constructor(props) {
        super(props);

        let data = this.props.dataset.json;
        data = JSON.parse(Utils.decodeUriComponent(data));

        Chart.register(CategoryScale);

        let labels = [];
        let datasets = [];

        for(const [key, value] of Object.entries(data)) {
            labels.push(key);
            datasets.push(value);
        }

        let chartData = {
            labels,
            datasets: [
                {
                    label: 'Bookings',
                    data: datasets,
                    fill: true,
                    borderColor: '#135e96',
                    backgroundColor: '#8bbde3',
                },
            ],
        };

        this.state = {
            data: chartData
        }
    }

    render() {
        return (
            <Line
                data={this.state.data}
                options={this.options}
            />
        );
    }
}