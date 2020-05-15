<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\View;
use App\Models\ViewGraph;
use App\Providers\DeviceServiceProvider;
use App\Providers\SensorServiceProvider;
use App\Providers\ViewGraphServiceProvider;
use App\Providers\ViewServiceProvider;

class ViewController extends Controller
{
    private $viewProvider;
    private $viewGraphProvider;
    private $sensorProvider;
    private $deviceProvider;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->viewProvider = new ViewServiceProvider();
        $this->viewGraphProvider = new ViewGraphServiceProvider();
        $this->sensorProvider = new SensorServiceProvider();
        $this->deviceProvider = new DeviceServiceProvider();
    }

    public function index()
    {
        $views = $this->viewProvider->findAll();
        return view('views.index', compact('views'));
    }

    public function show($viewId)
    {
        $view = $this->viewProvider->find($viewId);
        $graphs = $this->viewGraphProvider->findAllFromView($viewId);
        $devices = $this->deviceProvider->findAll();
        $auxDev = [];
        foreach ($devices as $d) {
            $sensors[$d->deviceId] = $this->sensorProvider->findAllFromDevice($d->deviceId);
            $auxDev[$d->deviceId] = $d->frequency;
        }
        $sensorsOnGraphs = [];
        foreach ($graphs as $g) {
            $found = [0 => false,1 => false];
            foreach ($devices as $d) {
                foreach ($sensors[$d->deviceId] as $s) {
                    if ($g->sensor1 == $s->sensorId) {
                        $sensorsOnGraphs[$g->viewGraphId][0] = $s;
                        $found[0] = true;
                    }
                    if ($g->sensor2 == $s->sensorId) {
                        $sensorsOnGraphs[$g->viewGraphId][1] = $s;
                        $found[1] = true;
                    }
                    if ($found[0] && $found[1]) {
                        break;
                    }
                }
                if ($found[0] && $found[1]) {
                    break;
                }
            }
        }
        return view('views.show', compact(['graphs','view','sensorsOnGraphs', 'sensors', 'devices', 'auxDev']));
    }

    public function destroy($userId)
    {
        return $this->viewProvider->destroy($userId) ?
            redirect(route('views.index'))->withErrors(['GoodDestroy' => 'View eliminata con successo']) :
            redirect(route('views.index'))->withErrors(['NotDestroy' => 'View non eliminata']);
    }

    public function store()
    {
        $data = request()->validate([
            'viewName' => 'required|string',
        ]);
        return $this->viewProvider->store(json_encode(['name' => $data['viewName']])) ?
            redirect(route('views.index'))->withErrors(['GoodCreate' => 'View creata con successo']) :
            redirect(route('views.index'))->withErrors(['NotCreate' => 'Creazione non avvenuta']);
    }
}
