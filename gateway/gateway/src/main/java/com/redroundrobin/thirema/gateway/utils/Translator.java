package com.redroundrobin.thirema.gateway.utils;

import com.google.gson.Gson;
import com.redroundrobin.thirema.gateway.models.Device;
import com.redroundrobin.thirema.gateway.models.Sensor;
import java.util.ArrayList;
import java.util.List;
import java.util.Optional;

public class Translator {
  private static final int ERR = -1;
  private static final int REQ = 0;
  private static final int RES = 1;

  private final List<Device> devices;

  public Translator() {
    devices = new ArrayList<>();
  }

  // Ritorna la lista dei dispositivi
  public List<Device> getDevices() {
    return devices;
  }

  // Ritorna la lista dei dati raccolti in formato JSON
  public String getJson() {
    return (new Gson()).toJson(devices);
  }

  // Viene controllato il pacchetto di risposta contiene i dati corretti, se passa i test allora il pacchetto viene aggiunto alla lista di pacchetti
  public boolean addSensor(byte[] packet, String gateway) {
    if (packet[1] == ERR || packet[1] == REQ || packet[1] == RES) {
      int id = Byte.toUnsignedInt(packet[0]);
      int sensorId = Byte.toUnsignedInt(packet[2]);
      int data = Byte.toUnsignedInt(packet[3]);
      long timestamp = System.currentTimeMillis();

      Optional<Device> optionalDevice = devices.stream().filter(device -> device.getDeviceId() == id).findFirst();
      // Controllo se il dispositivo è già presente nella lista dei dispositivi accumulati dal traduttore
      if (optionalDevice.isPresent()) {
        Device device = optionalDevice.get();

        Optional<Sensor> optionalSensor = device.getSensors().stream().filter(sensor -> sensor.getSensorId() == sensorId).findFirst();
        // Controllo se il sensore è già stato aggiunto alla lista
        if (optionalSensor.isPresent()) {
          optionalSensor.get().setData(data);
          optionalSensor.get().setTimestamp(timestamp);
        } else {
          Sensor sensor = new Sensor(sensorId, data);
          sensor.setTimestamp(timestamp);
          device.addSensor(sensor);
        }
      } else {
        // Il device non è ancora presente nella lista
        Device device = new Device(id, gateway);
        device.setLastSent(timestamp);
        Sensor sensor = new Sensor(sensorId, data);
        sensor.setTimestamp(timestamp);
        device.addSensor(sensor);
        devices.add(device);
      }

      return true;
    } else {
      return false;
    }
  }

  public void clearDevices() {
    devices.clear();
  }
}
