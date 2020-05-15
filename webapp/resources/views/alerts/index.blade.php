@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('alerts.index'))
@section('content')

<div class="container-fluid">
    <div class="d-sm-flex mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Alerts</h1>
    </div>
    @include('layouts.error')
    <div class="row">
        <div class="col-auto mb-4 ">
            <a href="{{route('dashboard.index')}}" class="btn btn-sm btn-danger btn-icon-split mr-3">
            <span class="icon text-white-50">
              <span class="fas fa-arrow-circle-left"></span>
            </span>
                <span class="text">Torna indietro</span>
            </a>
            @can('isMod')
                <a href="{{route('alerts.create')}}" class="btn btn-sm btn-success btn-icon-split">
                    <span class="icon text-white-50">
                      <span class="fas fa-plus-circle"></span>
                    </span>
                    <span class="text">Aggiungi alert</span>
                </a>
            @endcan
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-bell"></span> Lista alerts</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive-xl">
                            <table class="table table-bordered table-striped border-secondary">
                                <thead class="thead-dark table-borderless">
                                <tr>
                                    <th><span class="fas fa-list-ul"></span></th>
                                    <th>Dispositivo</th>
                                    <th>Sensore</th>
                                    <th>Soglia</th>
                                    <th>Valore</th>
                                    <th>Ultimo invio</th>
                                    @canany(['isMod', 'isAdmin'])
                                        <th class="bg-secondary"> </th>
                                    @endcanany
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($alertsWithSensors as $status => $a)
                                    @foreach($a as $list)
                                        <tr>
                                            <td><span class="logic-id">{{$list['alert']->alertId}}</span></td>
                                            <td><a href="{{route('devices.show', ['deviceId' => $list['device']->deviceId])}}">{{$list['device']->name}}</a></td>
                                            <td><a href="{{route('sensors.show', ['deviceId' => $list['device']->deviceId, 'sensorId' => $list['sensor']->realSensorId])}}">S<span class="real-id">{{$list['sensor']->realSensorId}}</span></td>
                                            <td>{{$list['alert']->getType()}}</td>
                                            <td>{{$list['alert']->threshold}}</td>
                                            <td>{{$list['alert']->lastSent?date("d/m/Y - H:i:s", strtotime($list['alert']->lastSent)):'-'}}</td>
                                            @can(['isMod'])
                                                <td class="text-center">
                                                    <a href="{{route('alerts.edit', ['alertId' => $list['alert']->alertId])}}" class="btn btn-sm btn-warning btn-icon-split">
                                                        <span class="icon text-white-50">
                                                          <span class="fas fa-edit"></span>
                                                        </span>
                                                        <span class="text">Modifica</span>
                                                    </a>
                                                </td>
                                            @endcan
                                            @can(['isAdmin'])
                                                <td class="text-center">
                                                    <a class="btn btn-sm btn-danger btn-icon-split" href="{{ route('alerts.destroy', ['alertId'=>$list['alert']->alertId]) }}"
                                                       onclick="event.preventDefault(); return confirm('Sei sicuro di voler rimuovere alert #{{ $list['alert']->alertId }}?') ?
                                                       document.getElementById('destroy-alert-{{ $list['alert']->alertId }}').submit() : false;">
                                                        <span class="icon text-white-50">
                                                          <span class="fas fa-trash-alt"></span>
                                                        </span>
                                                        <span class="text">Elimina alert</span>
                                                    </a>
                                                    <form id="destroy-alert-{{ $list['alert']->alertId }}" action="{{ route('alerts.destroy', ['alertId'=>$list['alert']->alertId]) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
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
