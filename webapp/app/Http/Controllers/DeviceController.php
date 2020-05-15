<?php

namespace App\Http\Controllers;

use App\Providers\DeviceServiceProvider;
use App\Providers\GatewayServiceProvider;
use App\Providers\SensorServiceProvider;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class DeviceController extends Controller
{
    private $gatewayProvider;
    private $deviceProvider;
    private $sensorProvider;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->gatewayProvider = new GatewayServiceProvider();
        $this->deviceProvider = new DeviceServiceProvider();
        $this->sensorProvider = new SensorServiceProvider();
    }

    public function create()
    {
        $gateways = $this->gatewayProvider->findAll();
        return view('devices.create', compact('gateways'));
    }

    public function edit($device)
    {
        $device = $this->deviceProvider->find($device) ?? [];
        $sensors = $this->sensorProvider->findAllFromDevice($device->deviceId) ?? [];
        $gateways = $this->gatewayProvider->findAll() ?? [];
        return view('devices.edit', compact(['device', 'sensors','gateways']));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $gateways = $this->gatewayProvider->findAll();
        $devicesOnGateways = [];
        foreach ($gateways as $g) {
            $sensors = [];
            $devices = $this->deviceProvider->findAllFromGateway($g->gatewayId);
            foreach ($devices as $d) {
                $sensors[$d->deviceId] = count($this->sensorProvider->findAllFromDevice($d->deviceId) ?? []);
            }
            $devicesOnGateways[$g->gatewayId] = ['gateway' => $g,
                                                'devices' => $devices,
                                                'sensors' => $sensors
            ];
        }
        return view('devices.index', compact('devicesOnGateways'));
    }

    /**
     * Display the specified resource.
     *
     * @param $device
     * @return Factory|View
     */
    public function show($deviceId)
    {
        $device = $this->deviceProvider->find($deviceId);
        $sensors = $this->sensorProvider->findAllFromDevice($device->deviceId);
        $gateway = $this->gatewayProvider->findAllFromDevice($device->deviceId)[0];
        return view('devices.show', compact(['device', 'sensors', 'gateway']));
    }

    public function store()
    {
        $data = request()->validate([
            'realDeviceId' => 'required|numeric',
            'name' => 'required|string',
            'gatewayId' => 'required|numeric',
            'frequency' => 'required|numeric|in:1,2,3,4,5',
            'sensorId.*' => 'nullable|numeric|required_with:sensorType.*',
            'sensorType.*' => 'nullable|string|required_with:sensorId.*',
            'enableCmd.*' => 'nullable|string|required_with:sensorId.*'
        ]);
        $data['realDeviceId'] = intval($data['realDeviceId']);
        $data['gatewayId'] = intval($data['gatewayId']);
        $data['frequency'] = intval($data['frequency']);
        $toSend = json_encode([
            'realDeviceId' => $data['realDeviceId'],
            'name' => $data['name'],
            'gatewayId' => $data['gatewayId'],
            'frequency' => $data['frequency']
        ]);
        if (!$this->deviceProvider->store($toSend)) {
            return redirect(
                route('devices.index')
            )->withErrors(['NotCreate' => 'Dispositivo e Sensori non creati']);
        }
        $data['sensorId'] = $data['sensorId'] ?? [];
        $data['sensorType'] = $data['sensorType'] ?? [];
        $data['enableCmd'] = $data['enableCmd'] ?? [];
        $numId = count($data['sensorId']);
        $numType = count($data['sensorType']);
        $numCmd = count($data['enableCmd']);
        if ($numId === $numType && $numId === $numCmd) {
            //fetch and filter of the new device
            $device = $this->deviceProvider->findFromGateway($data['gatewayId'], $data['realDeviceId']);
            if (!$this->insertSensors($data['sensorId'], $device, $data)) {
                return redirect(route('devices.index'))
                    ->withErrors(['NotCreate' => 'Dispositivo creato ma Sensori non creati']);
            }
        }
        return redirect(route('devices.index'))
            ->withErrors(['GoodCreate' => 'Dispositivo e Sensori creati con successo']);
    }

    public function destroy($deviceId)
    {
        return $this->deviceProvider->destroy($deviceId) ?
            redirect(route('devices.index'))
                ->withErrors(['GoodDestroy' => 'Dispositivo eliminato con successo']) :
            redirect(route('devices.index'))
                ->withErrors(['NotDestroy' => 'Dispositivo non eliminato']);
    }

    public function update($deviceId)
    {
        $oldDevice = $this->deviceProvider->find($deviceId);
        $data = request()->validate([
            'realDeviceId' => 'required|numeric',
            'name' => 'required|string',
            'gatewayId' => 'required|numeric',
            'frequency' => 'required|numeric|in:1,2,3,4,5',
            'sensorId.*' => 'nullable|numeric|required_with:sensorType.*',
            'sensorType.*' => 'nullable|string|required_with:sensorId.*',
            'enableCmd.*' => 'nullable|string|required_with:sensorId.*'
        ]);
        $data['realDeviceId'] = intval($data['realDeviceId']);
        $data['gatewayId'] = intval($data['gatewayId']);
        $data['frequency'] = intval($data['frequency']);
        $updateDeviceBody = [];
        ($oldDevice->realDeviceId !== $data['realDeviceId'])
            ? $updateDeviceBody['realDeviceId'] = $data['realDeviceId'] : null;
        ($oldDevice->name !== $data['name']) ? $updateDeviceBody['name'] = $data['name'] : null;
        ($oldDevice->gatewayId !== $data['gatewayId']) ? $updateDeviceBody['gatewayId'] = $data['gatewayId'] : null;
        ($oldDevice->frequency !== $data['frequency']) ? $updateDeviceBody['frequency'] = $data['frequency'] : null;
        if (!empty($updateDeviceBody)) {
            if (!$this->deviceProvider->update($deviceId, json_encode($updateDeviceBody))) {
                return redirect(route('devices.index'))
                    ->withErrors(['NotUpdate' => 'Dispositivo e Sensori non aggiornati']);
            }
        }
        $device = $this->deviceProvider->find($deviceId);
        $oldSensors = $this->sensorProvider->findAllFromDevice($deviceId);
        $oldSensorsId = [];
        $oldSensorsKeyed = [];
        foreach ($oldSensors as $s) {
            $oldSensorsId[] = $s->realSensorId;
            $oldSensorsKeyed[$s->realSensorId] = $s;
        }
        //ci sono dispositivi nel form
        $data['sensorId'] = $data['sensorId'] ?? [];
        $data['sensorType'] = $data['sensorType'] ?? [];
        $data['enableCmd'] = $data['enableCmd'] ?? [];
        $check = true;
        $numId = count($data['sensorId']);
        $numType = count($data['sensorType']);
        $numCmd = count($data['enableCmd']);
        if ($numId === $numType && $numId === $numCmd) {
            //se sono uguali
            $toInsert = array_diff($data['sensorId'], $oldSensorsId);
            $toDelete = array_diff($oldSensorsId, $data['sensorId']);
            $toModify = array_intersect($data['sensorId'], $oldSensorsId);
            $check = (
                $this->insertSensors($toInsert, $device, $data) &&
                $this->modifySensors($toModify, $device, $data, $oldSensorsKeyed) &&
                $this->deleteSensors($toDelete, $device)
            );
        }
        if (!(count($data['sensorId']) === count($data['sensorType'])) || !$check) {
            return redirect(route('devices.index'))->withErrors(['NotCreate' => 'Dispositivo aggiornato,
                    ma si e verificato un errore durante l\'aggiornamento dei sensori']);
        }
        return redirect(route('devices.index'))
            ->withErrors(['GoodUpdate' => 'Dispositivo e Sensori aggiornati con successo']);
    }

    private function insertSensors($toInsert, $device, $data)
    {
        foreach ($toInsert as $key => $value) {
            $toSend = json_encode([
                'deviceId' => $device->deviceId,
                'realSensorId' => intval($value),
                'type' => $data['sensorType'][$key],
                'cmdEnabled' => $data['enableCmd'][$key] === 'true' ? true : false
            ]);
            if (!$this->sensorProvider->store($device->deviceId, $toSend)) {
                return false;
            }
        }
        return true;
    }
    private function modifySensors($toModify, $device, $data, $oldSensorsKeyed)
    {
        foreach ($toModify as $key => $value) {
            $type = $data['sensorType'][$key];
            $cmd = $data['enableCmd'][$key];
            if ($oldSensorsKeyed[$value]->type !== $type || $oldSensorsKeyed[$value]->cmdEnabled !== $cmd) {
                $toSend = json_encode([
                    'type' => $type,
                    'cmdEnabled' => $cmd === 'true' ? true : false
                ]);
                if (!$this->sensorProvider->update($device->deviceId, $value, $toSend)) {
                    return false;
                }
            }
        }
        return true;
    }
    private function deleteSensors($toDelete, $device)
    {
        foreach ($toDelete as $i) {
            if (!$this->sensorProvider->destroy($device->deviceId, $i)) {
                return false;
            }
        }
        return true;
    }
}
