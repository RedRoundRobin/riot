@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('entities.show', $entity->entityId))
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800"> {{$entity->name}}</h1>
        </div>
        @include('layouts.error')
        <div class="row">
            <div class="col-auto mb-4 ">
                <a href="{{route('entities.index')}}" class="btn btn-sm btn-danger btn-icon-split">
                        <span class="icon text-white-50">
                          <span class="fas fa-arrow-circle-left"></span>
                        </span>
                    <span class="text">Torna indietro</span>
                </a>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-building"></span> Informazioni ente</h6>
                    </div>
                    <div class="card-body">
                       <ul>
                           <li><strong>ID logico:</strong> <span class="logic-id"></span>{{$entity->entityId}}</li>
                           <li><strong>Nome ente:</strong> {{$entity->name}}</li>
                           <li><strong>Luogo:</strong> {{$entity->location}}</li>
                           <li><strong>Status:</strong>
                               @if($entity->deleted===true)
                                   <span class="badge badge-danger">Eliminato</span>
                               @else
                                   <span class="badge badge-success">Attivo</span>
                               @endif</li>
                       </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <a href="#collapseAddSensor" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseAddSensor">
                        <h6 class="m-0 font-weight-bold text-success">
                            <span class="fas fa-plus-square"></span>
                            Aggiungi sensore
                        </h6>
                    </a>
                    <div class="collapse show" id="collapseAddSensor">
                        <div class="card-body">
                            <form method="POST" action="{{route('entities.updateSensors', ['entityId' => $entity->entityId])}}" id="updateSensors">
                                @csrf
                                @method('PUT')
                                <div class="form-group row">
                                    <label for="inputSensor" class="col-sm-3 col-form-label"><span class="fas fa-thermometer-half"></span> Sensore</label>
                                    <div class="col-sm-9">
                                        <div class="input-group mb-3">
                                            <select class="form-control @error('sensor') is-invalid @enderror" name="sensor" id="inputSensor">
                                                @foreach($devices as $d)
                                                   @foreach($sensors[$d->deviceId] as $s)
                                                        <option id="inputSensor{{$s->sensorId}}"
                                                                value="{{$s->sensorId}}"
                                                                data-real-id="{{$s->sensorId}}"
                                                                data-type="{{$s->type}}"
                                                                data-device="{{$s->device}}">
                                                            {{$s->type.' S@' . $s->realSensorId.' // '.$d->name . ' D#'.$s->device }}
                                                        </option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                            @error('sensor')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success btn-icon-split float-right mb-3" id="connectSensor">
                                    <span class="icon text-white-50">
                                      <span class="fas fa-plus-circle"></span>
                                    </span>
                                    <span class="text">Aggiungi sensore</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-users"></span> Lista membri ente</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered border-secondary">
                                <thead class="thead-dark table-borderless">
                                <tr>
                                    <th>Nome e Cognome</th>
                                    <th>Ruolo</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td><a href="{{route('users.show', ['userId' => $user->userId ])}}">{{$user->name}} {{$user->surname}}</a></td>
                                            <td><span class="text-info">{{$user->getRole()}}</span> </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-12 mb-4">
                <div class="card shadow ">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-thermometer-half"></span> Lista sensori autorizzati</h6>
                    </div>
                    <div class="card-body">
                        <p>Di seguito è riportata la lista dei sensori autorizzati per l'ente.</p>
                        <div class="table-responsive-md">
                            <table class="table table-striped table-bordered border-secondary">
                                <thead class="thead-dark table-borderless">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tipo</th>
                                        <th>Dispositivo</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="sensorsList">
                                    @foreach($sensorsEntity as $s)
                                        <tr id="sensore{{$s->sensorId}}">
                                            <td><a href="{{route('sensors.show', ['deviceId' => $s->device, 'sensorId' => $s->realSensorId ])}}">S<span class="real-id"></span>{{$s->realSensorId}}</td>
                                            <td>{{$s->type}}</td>
                                            <td><a href="{{route('devices.show', ['deviceId' => $s->device ])}}">D<span class="logic-id"></span>{{$s->device}}</a></td>
                                            <td>
                                                <button class="btn btn-sm btn-danger delete">
                                                    <span class="fas fa-trash"></span>
                                                    <input form="updateSensors" type="checkbox" value="{{$s->sensorId}}" checked style="display: none" name="sensors[]">
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-sm-flex ml-sm-auto">
                            <button type="submit" class="btn btn-success btn-icon-split" form="updateSensors">
                                <span class="icon text-white-50">
                                <span class="fas fa-save"></span>
                                 </span>
                                <span class="text">Salva modifiche</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

