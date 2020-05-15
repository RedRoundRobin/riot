@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('sensors.index', $device))
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800"> Sensori</h1>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-thermometer-half"></span> Lista sensori</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-dark table-borderless`">
                        <tr>
                            <th>Status</th>
                            <th>ID </th>
                            <th>ID interno</th>
                            <th>ID dispositivo</th>
                            <th>Tipo</th>
                            <th> </th>
                        </tr>
                        </thead>
                        <tfoot class="thead-dark table-borderless">
                        <tr>
                            <th>Status</th>
                            <th>ID </th>
                            <th>ID interno</th>
                            <th>ID dispositivo</th>
                            <th>Tipo</th>
                            <th> </th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($sensors as $sensor)
                            <tr>
                                <td><span class="badge badge-success">Attivo</span></td>
                                <td>{{$sensor->sensorId}}</td>
                                <td>{{$sensor->deviceSensorId}}</td>
                                <td>{{$sensor->deviceId}}</td>
                                <td>{{$sensor->type}}</td>
                                <td><a href="{{route('sensors.show', [
                                                                        'deviceId' => $sensor->deviceId,
                                                                        'sensorId' => $sensor->deviceSensorId
                                            ])}}" class="btn btn-success btn-icon-split">
                                        <span class="icon text-white-50">
                                          <span class="fas fa-info-circle"></span>
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
@endsection
