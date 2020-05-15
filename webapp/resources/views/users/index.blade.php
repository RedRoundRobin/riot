@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('users.index'))
@section('content')
    <div class="container-fluid">
            <div class="d-sm-flex mb-4">
                <h1 class="h3 mb-0 text-gray-800"> Gestione utenti</h1>
            </div>
        @include('layouts.error')
        <div class="d-sm-flex mb-4 ml-sm-auto">
            <a href="{{route('dashboard.index')}}" class="btn btn-sm btn-danger btn-icon-split mr-4">
                        <span class="icon text-white-50">
                          <span class="fas fa-arrow-circle-left"></span>
                        </span>
                <span class="text">Torna indietro</span>
            </a>
            @canany(['isAdmin', 'isMod'])
            <a href="{{route('users.create')}}" class="btn btn-sm btn-success btn-icon-split">
                    <span class="icon text-white-50">
                      <span class="fas fa-user-plus"></span>
                    </span>
                <span class="text">Crea nuovo utente</span>
            </a>
            @endcanany
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-users"></span> Lista utenti</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive-xl">
                    <table class="table border-secondary table-bordered table-striped dataTableUsers">
                        <thead class="thead-dark table-borderless">
                            <tr>
                                <th>Nome e cognome</th>
                                <th>Stato</th>
                                <th>Email</th>
                                <th>Ruolo</th>
                                @can('isAdmin')
                                    <th>Ente</th>
                                @endcan
                                <th class="bg-secondary"> </th>
                            </tr>
                        </thead>
                        <tbody>
                            @canany(['isAdmin', 'isMod'])
                                @foreach($usersWithEntity as $u)
                                    <tr>
                                        <td><a href="{{route('users.show', ['userId' => $u['user']->userId ])}}">{{$u['user']->name}} {{$u['user']->surname}}</a></td>
                                        <td>
                                            @if($u['user']->deleted)
                                                <span class="badge badge-danger">Disattivo</span>
                                            @else
                                                <span class="badge badge-success">Attivo</span>
                                            @endif
                                        </td>
                                        <td>{{$u['user']->email}}</td>
                                        <td>
                                            <span class="text-info">{{$u['user']->getRole()}}</span>
                                        </td>
                                        @can('isAdmin')
                                            <th>{{$u['entity']?$u['entity']->name:'-'}}</th>
                                        @endcan
                                        <td class="text-center">
                                            @if($u['user']->type < Auth::user()->type)
                                                <a href="{{route('users.edit', $u['user']->userId)}}" class="btn btn-sm btn-warning btn-icon-split">
                                                    <span class="icon text-white-50">
                                                      <span class="fas fa-user-edit"></span>
                                                    </span>
                                                    <span class="text">Modifica</span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endcanany
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
