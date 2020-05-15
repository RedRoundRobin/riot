<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Providers\DeviceServiceProvider;
use App\Providers\EntityServiceProvider;
use App\Providers\SensorServiceProvider;
use App\Providers\UserServiceProvider;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class EntityController extends Controller
{
    private $entityProvider;
    private $usersProvider;
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
        $this->entityProvider = new EntityServiceProvider();
        $this->usersProvider = new UserServiceProvider();
        $this->deviceProvider = new DeviceServiceProvider();
        $this->sensorProvider = new SensorServiceProvider();
    }

    public function create()
    {
        $entities = $this->entityProvider->findAll();
        return view('entities.create', compact(['entities']));
    }

    public function edit($entity)
    {
        $entity = $this->entityProvider->find($entity);
        return view('entities.edit', compact('entity'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $entities = $this->entityProvider->findAll();
        return view('entities.index', compact('entities'));
    }

    /**
     * Display the specified resource.
     *
     * @param $entity
     * @return Factory|View
     */
    public function show($entityId)
    {
        $entity = $this->entityProvider->find($entityId);
        $users = $this->usersProvider->findAllFromEntity($entity->entityId) ?? [];
        $devices = $this->deviceProvider->findAll();
        foreach ($devices as $d) {
            $sensors[$d->deviceId] = $this->sensorProvider->findAllFromDevice($d->deviceId) ?? [];
        }
        $sensorsEntity = $this->sensorProvider->findAllFromEntity($entity->entityId) ?? [];
        return view('entities.show', compact(['entity', 'users', 'sensors', 'devices', 'sensorsEntity']));
    }

    public function update($entityId)
    {
        $data = request()->validate([
            'name' => 'required|string',
            'location' => 'required|string'
        ]);
        return $this->entityProvider->update($entityId, json_encode($data)) ?
            redirect(route('entities.index'))->withErrors(['GoodUpdate' => 'Ente aggiornato con successo']) :
            redirect(route('entities.index'))->withErrors(['NotUpdate' => 'Ente non aggiornato']);
    }
    public function destroy($entityId)
    {
        return $this->entityProvider->destroy($entityId) ?
            redirect(route('entities.index'))->withErrors(['GoodDestroy' => 'Ente eliminato con successo']) :
            redirect(route('entities.index'))->withErrors(['NotDestroy' => 'Ente non eliminato']);
    }
    public function store()
    {
        $data = request()->validate([
            'name' => 'required|string',
            'location' => 'required|string'
        ]);
        return $this->entityProvider->store(json_encode($data)) ?
            redirect(route('entities.index'))->withErrors(['GoodUpdate' => 'Ente creato con successo']) :
            redirect(route('entities.index'))->withErrors(['NotUpdate' => 'Ente non creato']);
    }

    public function updateSensors($entityId)
    {
        $data = request()->validate([
            'sensors.*' => 'required|numeric'
        ]);
        $newSensors = $data['sensors'] ?? [];
        $sensors = $this->sensorProvider->findAllFromEntity($entityId) ?? [];
        $oldSensors = [];
        foreach ($sensors as $s) {
            $oldSensors[] = $s->sensorId;
        }

        $toInsert = array_values(array_map('intval', (array_diff($newSensors, $oldSensors)))) ?? [];
        $toDelete = array_values(array_map('intval', (array_diff($oldSensors, $newSensors)))) ?? [];

        if (empty($toInsert) && empty($toDelete)) {
            return redirect(route('entities.show', ['entityId' => $entityId]))
                ->withErrors(['GoodUpdate' => 'Sensori aggiornati con successo']);
        }
        $toSend = [
            'enableOrDisableSensors' => true,
            'toInsert' => $toInsert,
            'toDelete' => $toDelete
        ];
        return $this->entityProvider->update($entityId, json_encode($toSend)) ?
            redirect(route('entities.show', ['entityId' => $entityId]))
                ->withErrors(['GoodUpdate' => 'Sensori aggiornati con successo']) :
            redirect(route('entities.show', ['entityId' => $entityId]))
                ->withErrors(['NotUpdate' => 'Sensori non aggiornati']);
    }
}
