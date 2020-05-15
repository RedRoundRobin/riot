@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('gateways.index'))
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800"> Gestione gateways </h1>
        </div>
        @include('layouts.error')
        <div class="row">
            <div class="col-auto mb-4">
                <a href="{{route('dashboard.index')}}" class="btn btn-sm btn-danger btn-icon-split">
                            <span class="icon text-white-50">
                              <span class="fas fa-arrow-circle-left"></span>
                            </span>
                    <span class="text">Torna indietro</span>
                </a>
            </div>
            @can(['isAdmin'])
                <div class="col-auto mb-4">
                    <a href="{{route('gateways.create')}}" class="btn btn-sm btn-success btn-icon-split">
                        <span class="icon text-white-50">
                          <span class="fas fa-plus-circle"></span>
                        </span>
                        <span class="text">Aggiungi gateway</span>
                    </a>
                </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-dungeon"></span> Lista gateway</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info"><span class="fas fa-info-circle"></span>
                    Una volta eseguite le modifiche a un dispositivo, è possibile inviare nuovamente la configurazione a un
                    gateway, premendo l'apposito pulsante nella tabella.
                </div>
                <div class="table-responsive-xl">
                    <table class="table table-bordered table-striped border-secondary">
                        <thead class="thead-dark table-borderless">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Numero Dispositivi</th>
                            <th>Ultimo invio config.</th>
                            <th class="bg-secondary" width="200"> </th>
                            <th class="bg-secondary" width="100"> </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($gatewaysWithDevices as $gWd)
                            <tr>
                                <td><a href="{{route('gateways.show', ['gatewayId' => $gWd['gateway']->gatewayId ])}}">
                                        <span class="logic-id"></span>{{$gWd['gateway']->gatewayId}} </a></td>
                                <td>
                                    <a href="{{route('gateways.show', ['gatewayId' => $gWd['gateway']->gatewayId ])}}">
                                        <span class="text-gray-800">{{substr($gWd['gateway']->name, 0, 3)}}</span>{{substr($gWd['gateway']->name, 3)}}
                                </td>
                                <td>{{count($gWd['devices'])}}</td>
                                <td class="small">{{$gWd['gateway']->lastSent?date("d/m/Y - H:i:s", strtotime($gWd['gateway']->lastSent)):'-'}}</td>
                                <td class="text-center">
                                    <a href="#" onclick="event.preventDefault();
                                    return confirm('Sei sicuro di voler inviare la configurazione al gateway #{{$gWd['gateway']->gatewayId}} ?') ? document.getElementById('config{{$gWd['gateway']->gatewayId}}').submit() : false;"
                                       class="btn btn-sm btn-primary btn-icon-split">
                                        <span class="icon text-white-50">
                                          <span class="fas fa-paper-plane"></span>
                                        </span>
                                        <span class="text">Invia config.</span>
                                    </a>
                                    <form id="config{{$gWd['gateway']->gatewayId}}" action="{{ route('gateways.config', ['gatewayId' => $gWd['gateway']->gatewayId]) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('PUT')
                                    </form>
                                </td>
                                <td>
                                    <a href="{{route('gateways.edit', ['gatewayId' => $gWd['gateway']->gatewayId ])}}"
                                       class="btn btn-sm btn-warning btn-icon-split">
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
        @endcan
    </div>
@endsection
