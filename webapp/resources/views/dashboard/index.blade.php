@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('dashboard.index'))
@section('content')
    <div class="container-fluid">

        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800"> Dashboard</h1>
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Utenti attivi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$stats->activeMembers}}</div>
                            </div>
                            <div class="col-auto">
                                <span class="fas fa-users fa-2x text-gray-500"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">utenti attivi nel tuo ente</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$stats->entityActiveMembers??"-"}}</div>
                            </div>
                            <div class="col-auto">
                                <span class="fas fa-user-friends fa-2x text-gray-500"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">utenti registrati</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$stats->registeredUsers}}</div>
                            </div>
                            <div class="col-auto">
                                <span class="fas fa-user-plus fa-2x text-gray-500"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">utenti registrati nel tuo ente</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$stats->entityRegisteredUsers??"-"}}</div>
                            </div>
                            <div class="col-auto">
                                <span class="fas fa-user-tag fa-2x text-gray-500"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">dispositivi registrati</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$stats->registeredDevices}}</div>
                            </div>
                            <div class="col-auto">
                                <span class="fas fa-shapes fa-2x text-gray-500"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">dispositivi del tuo ente</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$stats->entityRegisteredDevices??"-"}}</div>
                            </div>
                            <div class="col-auto">
                                <span class="fas fa-microchip fa-2x text-gray-500"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">enti presenti</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$stats->entitiesNumber}}</div>
                            </div>
                            <div class="col-auto">
                                <span class="far fa-building fa-2x text-gray-500"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <span class="far fa-smile"></span>
                            Dettagli utente
                        </h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><span class="fas fa-signature"></span> <strong>Nome e Cognome:</strong> {{$user->name}} {{$user->surname}}</li>
                        <li class="list-group-item"><span class="fas fa-envelope"></span> <strong>Indirizzo email:</strong> {{$user->email}}</li>
                        <li class="list-group-item"><span class="fas fa-user-tag"></span> <strong>Ruolo:</strong> {{$user->getRole()}}</li>
                        <li class="list-group-item"><span class="far fa-building"></span> <strong>Ente di appartenenza:</strong> {{$entity->name??"-"}}</li>
                        <li class="list-group-item"><span class="fas fa-link"></span> <strong>Indirizzo IP:</strong> &nbsp; <code>{{ request()->ip() }}</code></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold">
                            <span class="far fa-life-ring"></span>
                            Supporto tecnico
                        </h6>
                    </div>
                    <div class="card-body">
                        <p>In caso di problemi, si prega di contattare il supporto tecnico al seguente indirizzo email:</p>
                        <ul>
                            <li><a href="mailto:redroundrobin.site@gmail.com">redroundrobin.site@gmail.com</a></li>
                        </ul>
                        <p>In alternativa, è possibile contattarci direttamente anche tramite l'organizzazione su GitHub:</p>
                        <ul>
                            <li><a href="https://github.com/RedRoundRobin">github.com/RedRoundRobin</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
