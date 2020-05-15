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
    </div>
</template>

<script>
export default {
    props: {
        sensor: { type: Object, default: null },
        alerts: { type: Array, default: Array.from([]) },
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
            newDataSeries: [],
        };
        this.chartOptions = {
            annotations: {
                yaxis: this.generateSensorLine(),
            },
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
            this.fetchNewData();
            this.series = [
                {
                    name: this.sensor.type,
                    data: this.vars.newDataSeries,
                },
            ];
        }, this.frequency * 1000);
    },
    methods: {
        generateSensorLine() {
            const temp = [];
            this.alerts.forEach((alert) =>
                temp.push({
                    y: alert.threshold,
                    borderColor: "#00E396",
                    label: {
                        borderColor: "#00E396",
                        style: {
                            color: "#fff",
                            background: "#00E396",
                        },
                        text:
                            "Alert #" + alert.alertId + " @ " + alert.threshold,
                        position: "left",
                        offsetX: 60,
                    },
                })
            );
            return temp;
        },
        fetchNewData() {
            axios
                .get("/data/sensors/" + this.sensor.sensorId)
                .then((response) => {
                    this.vars.newDataSeries.push([
                        new Date(
                            response.data.time.slice(0, -9) + "Z"
                        ).getTime(),
                        response.data.value,
                    ]);
                });
        },
        fetchOldData(howMany) {
            axios
                .get(
                    "/data/sensors?sensors=" +
                        this.sensor.sensorId +
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
                            response.data[sensor].forEach((data) =>
                                this.vars.newDataSeries.push([
                                    new Date(
                                        data.time.slice(0, -9) + "Z"
                                    ).getTime(),
                                    data.value,
                                ])
                            );
                        }
                    }
                });
        },
    },
};
</script>
