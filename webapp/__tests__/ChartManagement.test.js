window.axios = require("axios");

import { mount } from "@vue/test-utils";
import DoubleChart from "../resources/js/components/DoubleChart.vue";
import SingleChart from "../resources/js/components/SingleChart";

describe("DoubleChart", () => {
    test("is a Vue instance", () => {
        const sensor2 =
            '{"type":"stick","realSensorId":1,"device":1,"sensorId":1}';
        const sensor1 =
            '{"type":"stick","realSensorId":1,"device":1,"sensorId":1}';
        const chart = mount(DoubleChart, {
            propsData: { sensor2, sensor1 },
        });
        expect(chart.isVueInstance()).toBeTruthy();
    });
});

describe("SingleChart", () => {
    test("is a Vue instance", () => {
        const sensor =
            '{"type":"stick","realSensorId":1,"device":1,"sensorId":1}';
        const chart = mount(SingleChart, {
            propsData: { sensor },
        });
        expect(chart.isVueInstance()).toBeTruthy();
    });
});
