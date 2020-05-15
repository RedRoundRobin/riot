@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('sensors.show', $device->deviceId, $sensor->realSensorId))
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex mb-4">
        <h1 class="h4 mb-0 text-gray-800"> Sensore <span class="real-id">{{$sensor->realSensorId}}</span> del dispositivo <span class="logic-id">{{$sensor->device}}</span> </h1>
    </div>
    <div class="d-inline-block mt-2 mb-4 px-0">
        <a href="{{route('devices.show', $sensor->device)}}" class="btn btn-sm btn-danger btn-icon-split">
                <span class="icon text-white-50">
                    <span class="fas fa-arrow-circle-left"></span>
                </span>
            <span class="text">Torna indietro</span>
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-chart-area"></span> Dati sensore real-time</h6>
                </div>
                <div class="card-body">
                    <single-chart
                        :sensor="{{json_encode($sensor)}}"
                        :frequency ="{{$device->frequency}}"
                        @canany(['isUser', 'isMod']) :alerts = "{{json_encode($alerts['enable'])}}" @endcanany
                    ></single-chart>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-thermometer-half"></span> Informazioni sensore</h6>
                </div>
                <div class="card-body">
                    <ul>
                        <li><strong>ID logico sensore:</strong> S<span class="logic-id">{{$sensor->sensorId}}</span></li>
                        <li><strong>ID reale sensore:</strong> S<span class="real-id">{{$sensor->realSensorId}}</span></li>
                        <li><strong>Tipo sensore:</strong> {{$sensor->type}}</li>
                        <li><strong>Dispositivo di appartenenza:</strong> <a href="{{route('devices.show', $sensor->device)}}">D<span class="logic-id">{{$sensor->device}}</span></a></li>
                        <li><strong>Invio/ricezione comandi:</strong> {{$sensor->cmdEnabled?'Abilitato' : 'Disabilitato'}}</li>
                    </ul>
                </div>
            </div>
        </div>

        @can(['isAdmin'])
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger"><span class="fas fa-building"></span> Lista enti autorizzati</h6>
                </div>
                <div class="card-body">
                    <p>Il sensore è stato abilitato per i seguenti enti:</p>
                    <div class="table-responsive-lg">
                        <table class="table table-striped table-bordered border-secondary">
                            <thead class="thead-dark table-borderless">
                            <tr>
                                <th>Nome</th>
                                <th>Luogo</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($entities as $entity)
                                    <tr>
                                        <td><a href="{{route('entities.show', ['entityId' => $entity->entityId ])}}">{{$entity->name}}</a></td>
                                        <td>{{$entity->location}}</td>
                                        <td>
                                            @if($entity->deleted===true)
                                                <span class="badge badge-danger">Eliminato</span>
                                            @else
                                                <span class="badge badge-success">Attivo</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>

</div>
@endsection
