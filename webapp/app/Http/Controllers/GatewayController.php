<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use App\Providers\DeviceServiceProvider;
use App\Providers\GatewayServiceProvider;
use App\Providers\SensorServiceProvider;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class GatewayController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $gateways = $this->gatewayProvider->findAll();
        $gatewaysWithDevices = [];
        foreach ($gateways as $g) {
            $gatewaysWithDevices[$g->gatewayId]['gateway'] = $g;
            $gatewaysWithDevices[$g->gatewayId]['devices'] = $this->deviceProvider->findAllFromGateway($g->gatewayId);
        }
        return view('gateways.index', compact('gatewaysWithDevices'));
    }

    /**
     * Display the specified resource.
     *
     * @param $gateway
     * @return Factory|View
     */
    public function show($gateway)
    {
        $gateway = $this->gatewayProvider->find($gateway);
        $devices = $this->deviceProvider->findAllFromGateway($gateway->gatewayId);
        $devicesWithSensors = [];
        foreach ($devices as $d) {
            $devicesWithSensors[$d->deviceId]['device'] = $d;
            $devicesWithSensors[$d->deviceId]['sensors'] = $this->sensorProvider->findAllFromDevice($d->deviceId) ?? [];
        }
        return view('gateways.show', compact(['gateway', 'devicesWithSensors']));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        return view('gateways.create');
    }

    public function edit($gateway)
    {
        $gateway = $this->gatewayProvider->find($gateway);
        return view('gateways.edit', compact('gateway'));
    }

    public function store()
    {
        $data = request()->validate([
            'name' => 'required|string|regex:/(gw_)([A-Za-z0-9_-]+){1,27}/'
        ]);
        return $this->gatewayProvider->store(json_encode($data)) ?
            redirect(route('gateways.index'))->withErrors(['GoodCreate' => 'Gateway creato con successo']) :
            redirect(route('gateways.index'))->withErrors(['NotCreate' => 'Gateway non creato']);
    }

    public function update($gatewayId)
    {
        $currentGateway = $this->gatewayProvider->find($gatewayId);
        $data = request()->validate([
            'name' => 'required|string|regex:/(gw_)([A-Za-z0-9_-]+){1,27}/'
        ]);
        if ($currentGateway->name == $data['name']) {
            return redirect(route('gateways.index'))
                ->withErrors(['GoodUpdate' => 'Gateway aggiornato con successo']);
        }
        return $this->gatewayProvider->update($gatewayId, json_encode($data)) ?
            redirect(route('gateways.index'))->withErrors(['GoodUpdate' => 'Gateway aggiornato con successo']) :
            redirect(route('gateways.index'))->withErrors(['NotUpdate' => 'Gateway non aggiornato']);
    }


    public function destroy($gatewayId)
    {
        return $this->gatewayProvider->destroy($gatewayId) ?
            redirect(route('gateways.index'))->withErrors(['GoodDestroy' => 'Gateway eliminato con successo']) :
            redirect(route('gateways.index'))->withErrors(['NotDestroy' => 'Gateway non eliminato']);
    }

    public function sendConfig($gatewayId)
    {
        return $this->gatewayProvider->sendConfig($gatewayId) ?
            redirect(route('gateways.index'))->withErrors(['GoodDestroy' => 'Configurazione inviata con successo']) :
            redirect(route('gateways.index'))->withErrors(['NotDestroy' => 'Configurazione non inviata']);
    }
}
