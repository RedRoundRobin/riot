@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('devices.show', $device->deviceId))
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex mb-4">
    <h1 class="h3 mb-0 text-gray-800"> {{$device->name}}</h1>
    </div>
    <div class="row">
        <div class="col-auto mb-4 ">
            <a href="{{route('devices.index')}}" class="btn btn-sm btn-danger btn-icon-split mr-3">
                        <span class="icon text-white-50">
                          <span class="fas fa-arrow-circle-left"></span>
                        </span>
                <span class="text">Torna indietro</span>
            </a>
            @can('isAdmin')
            <a href="{{route('devices.edit', ['deviceId' => $device->deviceId ])}}" class="btn btn-sm btn-warning btn-icon-split">
                        <span class="icon text-white-50">
                          <span class="fas fa-edit"></span>
                        </span>
                <span class="text">Modifica</span>
            </a>
            @endcan
        </div>
    </div>
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-microchip"></span> Informazioni dispositivo</h6>
                </div>
                <div class="card-body">
                    <ul>
                        <li><strong>ID logico dispositivo:</strong> D<span class="logic-id">{{$device->deviceId}}</span></li>
                        <li><strong>ID reale dispositivo:</strong> D<span class="real-id">{{$device->realDeviceId}}</span></li>
                        <li><strong>Nome dispositivo:</strong> {{$device->name}}</li>
                        <li><strong>Gateway di appartenenza:</strong> {{$gateway->name}}</li>
                        <li><strong>Numero di sensori:</strong> {{count($sensors)}}</li>
                        <li><strong>Frequenza di prelievo dati:</strong> {{$device->frequency}}s</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <span class="fas fa-thermometer-half"></span>
                        Lista sensori del dispositivo</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive-lg">
                        <table class="table border-secondary table-bordered table-striped">
                            <thead class="thead-dark table-borderless">
                                <tr>
                                    <th>ID reale</th>
                                    <th>ID logico</th>
                                    <th>Tipo di dato</th>
                                    <th>Invio comandi</th>
                                    <th class="bg-secondary"> </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($sensors as $sensor)
                                <tr>
                                    <td><a href="{{route('sensors.show', [
                                            'deviceId' => $device->deviceId,
                                            'sensorId' => $sensor->realSensorId
                                        ])}}">S<span class="real-id">{{$sensor->realSensorId}}</span></a></td>
                                    <td>S<span class="logic-id">{{$sensor->sensorId}}</span></td>
                                    <td>{{$sensor->type}}</td>
                                    <td>{{($sensor->cmdEnabled) ? 'Abilitato' : 'Disabilitato'}}</td>
                                    <td class="text-center"><a href="{{route('sensors.show', [
                                            'deviceId' => $device->deviceId,
                                            'sensorId' => $sensor->realSensorId
                                        ])}}" class="btn btn-sm btn-info btn-icon-split">
                                            <span class="icon text-white-50">
                                              <span class="fas fa-chart-area"></span>
                                            </span>
                                            <span class="text">Dettagli</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
