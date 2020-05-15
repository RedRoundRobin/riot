<template>
    <div>
        <apexchart
            ref="RTChart"
            height="400"
            type="line"
            :options="chartOptions"
            :series="series"
        >
        </apexchart>
        <div ref="variance"></div>
    </div>
</template>

<script>
import covariance from "@elstats/covariance";
import Pearson from "correlation-rank";
import Spearman from "spearman-rho";
export default {
    props: {
        sensor1: { type: Object, default: null },
        sensor2: { type: Object, default: null },
        variance: { type: Number, default: 0 },
        frequency: { type: Number, default: 3 },
    },
    data: function () {
        return {
            chartOptions: {},
            series: [],
        };
    },
    created() {
        this.vars = {
            newDataSeries: [this.sensor1.sensorId, this.sensor2.sensorId],
            VarData: [this.sensor1.sensorId, this.sensor2.sensorId],
        };
        this.vars.newDataSeries[this.sensor1.sensorId] = [];
        this.vars.newDataSeries[this.sensor2.sensorId] = [];
        this.vars.VarData[this.sensor1.sensorId] = [];
        this.vars.VarData[this.sensor2.sensorId] = [];
        this.chartOptions = {
            chart: {
                type: "line",
                height: 400,
                toolbar: {
                    show: false,
                },
                zoom: {
                    enabled: false,
                },
            },
            stroke: {
                curve: "smooth",
            },
            tooltip: {
                x: {
                    format: "HH:mm:ss - dd/MM/yyyy",
                },
            },
            yaxis: {
                title: {
                    text: "Valore",
                },
            },
            xaxis: {
                type: "datetime",
                range: 180000, // mantiene in memoria 30 secondi
                tickPlacement: "between",
                title: {
                    text: "Tempo",
                },
                labels: {
                    format: "HH:mm:ss - dd/MM/yyyy",
                },
            },
        };
        this.series = this.fetchOldData(20);
    },
    mounted() {
        setInterval(() => {
            this.fetchNewData(this.sensor1.sensorId);
            this.fetchNewData(this.sensor2.sensorId);
            this.series = [
                {
                    name: this.sensor1.type,
                    data: this.vars.newDataSeries[this.sensor1.sensorId],
                },
                {
                    name: this.sensor2.type,
                    data: this.vars.newDataSeries[this.sensor2.sensorId],
                },
            ];
            this.calculateVariance();
        }, this.frequency * 1000);
    },
    methods: {
        fetchOldData(howMany) {
            axios
                .get(
                    "/data/sensors?sensors[]=" +
                        this.sensor1.sensorId +
                        "&sensors[]=" +
                        this.sensor2.sensorId +
                        "&limit=" +
                        howMany
                )
                .then((response) => {
                    for (const sensor in response.data) {
                        if (
                            Object.prototype.hasOwnProperty.call(
                                response.data,
                                sensor
                            )
                        ) {
                            response.data[sensor].forEach((data) => {
                                this.vars.newDataSeries[sensor].push([
                                    new Date(
                                        data.time.slice(0, -9) + "Z"
                                    ).getTime(),
                                    data.value,
                                ]);
                                this.vars.VarData[sensor].push(data.value);
                            });
                        }
                    }
                });
        },
        fetchNewData(sensor) {
            axios.get("/data/sensors/" + sensor).then((response) => {
                this.vars.newDataSeries[sensor].push([
                    new Date(response.data.time.slice(0, -9) + "Z").getTime(),
                    response.data.value,
                ]);
                this.vars.VarData[sensor].push(response.data.value);
            });
        },
        calculateVariance() {
            const variance = [
                "Nessuna",
                "Covarianza",
                "Correlazione di Pearson",
                "Correlazione di Spearman",
            ];
            let calc = NaN;
            if (
                this.vars.VarData[this.sensor1.sensorId].length ==
                this.vars.VarData[this.sensor2.sensorId].length
            ) {
                switch (this.variance) {
                    case 1:
                        calc = covariance(
                            this.vars.VarData[this.sensor1.sensorId],
                            this.vars.VarData[this.sensor2.sensorId]
                        );
                        break;
                    case 2:
                        calc = Pearson.rank(
                            this.vars.VarData[this.sensor1.sensorId],
                            this.vars.VarData[this.sensor2.sensorId]
                        );
                        break;
                    case 3:
                        new Spearman(
                            this.vars.VarData[this.sensor1.sensorId],
                            this.vars.VarData[this.sensor2.sensorId]
                        )
                            .calc()
                            .then((value) => {
                                calc = value;
                                this.$refs.variance.innerHTML =
                                    "<hr> <span class='fas fa-project-diagram'></span> <strong>" +
                                    variance[this.variance] +
                                    "</strong>: " +
                                    calc.toFixed(3);
                            });
                        break;
                    default:
                        calc = NaN;
                        break;
                }
            }
            if (!isNaN(calc)) {
                this.$refs.variance.innerHTML =
                    "<hr> <span class='fas fa-project-diagram'></span> <strong>" +
                    variance[this.variance] +
                    "</strong>: " +
                    calc.toFixed(3);
            }
        },
    },
};
</script>
