const sensorsList = document.querySelector("#sensorsList");
const addSensor = document.querySelector("#addSensor");
const connectSensor = document.querySelector("#connectSensor");
const addDevice = document.querySelector("#addDevice");
const form = document.querySelector("#sensorForm");
const save = document.querySelector("#save");
let trashes = document.querySelectorAll(".delete");

if (addSensor !== null) {
    addSensor.addEventListener("click", (e) => {
        e.preventDefault();
        const sensorIdValue = document.querySelector("#inputSensorId").value;
        const hasSensor =
            sensorsList.querySelector("#sensore" + sensorIdValue) != null;
        if (!hasSensor) {
            const sensorTypeValue = document.querySelector("#inputSensorType")
                .value;
            const receiveCommand = document.querySelector("#commandCheck")
                .value;
            let select =
                '<select class="form-control" required name="enableCmd[]">';

            if (receiveCommand === "true") {
                select += '<option selected value="true">Abilitato</option>';
                select += '<option value="true">Disabilitato</option>';
            } else {
                select +=
                    '<option selected value="false">Disabilitato</option>';
                select += '<option value="true">Abilitato</option>';
            }
            select += " </select>";

            if (sensorIdValue !== "" && sensorTypeValue !== "") {
                sensorsList.innerHTML += `
            <div id="sensore${sensorIdValue}" class="form-group row bg-gray-200 pt-3">
                <label class="col-lg-3 col-form-label">
                    <span class="fas fa-thermometer-half mx-1"></span>Sensore <span class="real-id"></span> ${sensorIdValue}
                </label>
                <label class="col-lg-1 col-form-label">
                    <span class="real-id"></span> ID
                </label>
                <div class="col-lg-1">
                    <input type="text" class="form-control" placeholder="Id sensore" readonly="readonly" value="${sensorIdValue}" name="sensorId[]">
                </div>
                <label class="col-lg-1 col-form-label">
                    <span class="fas fa-tape mx-1"></span> Tipo
                </label>
                <div class="col-lg-2">
                    <input type="text" class="form-control" placeholder="Tipo di sensore" required value="${sensorTypeValue}" name="sensorType[]">
                </div>
                <label class="col-lg-1 col-form-label">
                    <span class="fas fa-satellite-dish mx-1"></span> CMD
                </label>
                <div class="col-lg-2">
                    ${select}
                </div>
                <div class="col-lg-1 col-form-label d-none d-lg-block text-center">
                    <button class="btn btn-sm btn-danger delete">
                        <span class="fas fa-trash"></span>
                    </button>
                </div>
                <div class="col-lg-1 mt-2 d-lg-none my-1">
                    <button class="btn btn-sm btn-danger btn-icon-split delete">
                        <span class="fas fa-trash icon text-white-50"></span>
                        <span class="text">Elimina sensore</span>
                    </button>
                </div>
            </div>
        `;
                trashes = document.querySelectorAll(".delete");
                // eliminazione sensore aggiunto
                trashes.forEach((trash) => {
                    trash.addEventListener("click", (e) => {
                        e.preventDefault();
                        trash.parentElement.parentElement.remove();
                    });
                    form.reset();
                });
            } else {
                alert("Id e tipo di sensore necessitano di un valore");
            }
        } else {
            alert("L'Id del sensore deve essere univoco");
        }
    });
}

if (addDevice !== null) {
    addDevice.addEventListener("click", (e) => {
        const deviceIdValue = document.querySelector("#inputDeviceId").value;
        const deviceNameValue = document.querySelector("#inputDeviceName")
            .value;
        if (deviceIdValue !== "" && deviceNameValue !== "") {
            alert("Dispositivo aggiunto correttamente");
        } else {
            e.preventDefault();
            alert("Id e nome del dispositivo necessitano di un valore");
        }
    });
}

if (connectSensor !== null) {
    connectSensor.addEventListener("click", (e) => {
        e.preventDefault();
        const sensorIdValue = document.querySelector("#inputSensor").value;
        const sensorDetails = document.querySelector(
            "#inputSensor" + sensorIdValue
        );
        const hasSensor =
            sensorsList.querySelector("#sensore" + sensorIdValue) != null;
        if (!hasSensor) {
            sensorsList.innerHTML += `
                <tr id="sensore${sensorIdValue}" class="bg-gray-200">
                    <td>S<span class="real-id"></span>${sensorIdValue}</td>
                    <td>${sensorDetails.dataset.type}</td>
                    <td>D<span class="logic-id"></span>${sensorDetails.dataset.device}</td>
                    <td>
                        <button class="btn btn-sm btn-danger delete">
                            <span class="fas fa-trash"></span>
                            <input form="updateSensors" type="checkbox" value="${sensorIdValue}" checked style="display: none" name="sensors[]">
                        </button>
                    </td>
                </tr>
            `;
            trashes = document.querySelectorAll(".delete");
            // eliminazione sensore aggiunto
            trashes.forEach((trash) => {
                trash.addEventListener("click", (e) => {
                    e.preventDefault();
                    trash.parentElement.parentElement.remove();
                });
            });
        } else {
            alert("Il sensore è già assegnato a questo ente");
        }
    });
}

trashes = document.querySelectorAll(".delete");
trashes.forEach((trash) => {
    trash.addEventListener("click", (e) => {
        e.preventDefault();
        trash.parentElement.parentElement.remove();
    });
});
