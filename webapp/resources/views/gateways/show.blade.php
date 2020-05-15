@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('gateways.show', $gateway->gatewayId))
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800"> {{$gateway->name}}</h1>
        </div>
        <div class="row">
            <div class="col-auto mb-4 ">
                <a href="{{route('gateways.index')}}" class="btn btn-sm btn-danger btn-icon-split">
                        <span class="icon text-white-50">
                          <span class="fas fa-arrow-circle-left"></span>
                        </span>
                    <span class="text">Torna indietro</span>
                </a>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-dungeon"></span> Informazioni Gateway</h6>
            </div>
            <div class="card-body">
                <ul>
                    <li><strong>ID logico:</strong> <span class="logic-id">{{$gateway->gatewayId}}</span></li>
                    <li><strong>Nome gateway:</strong> {{$gateway->name}}</li>
                    <li><strong>Numero di dispositivi censiti:</strong> {{count($devicesWithSensors)}}</li>
                    <li><strong>Ultimo invio della configurazione:</strong> {{$gateway->lastSent??'-'}}</li>
                </ul>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-microchip"></span> Lista dispositivi censiti dal gateway</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered border-secondary">
                        <thead class="thead-dark table-borderless border-secondary">
                        <tr>
                            <th><span class="fas fa-list-ul"></span></th>
                            <th>Nome</th>
                            <th>Sensori</th>
                            <th>Frequenza</th>
                            <th class="bg-secondary"> </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($devicesWithSensors as $device)
                            <tr>
                                <td><a href="{{route('devices.show', ['deviceId' => $device['device']->deviceId ])}}"> D<span class="logic-id"></span>{{$device['device']->deviceId}}</a></td>
                                <td> <a href="{{route('devices.show', ['deviceId' => $device['device']->deviceId ])}}">{{$device['device']->name}}</a></td>
                                <td>{{count($device['sensors'])}}</td>
                                <td>{{$device['device']->frequency}}s</td>
                                <td><a href="{{route('devices.edit', ['deviceId' => $device['device']->deviceId ])}}" class="btn btn-sm btn-warning btn-icon-split">
                                                <span class="icon text-white-50">
                                                  <span class="fas fa-edit"></span>
                                                </span>
                                        <span class="text">Modifica</span>
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
@endsection
