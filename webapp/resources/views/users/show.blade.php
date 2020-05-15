@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('users.show', $user->userId))
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800"> Profilo di {{$user->name . ' ' . $user->surname}}</h1>
        </div>
        @include('layouts.error')
        <div class="d-sm-flex mb-4 ml-sm-auto">
            <a href="{{route('users.index')}}" class="btn btn-sm btn-danger btn-icon-split">
                <span class="icon text-white-50">
                  <span class="fas fa-arrow-circle-left"></span>
                </span>
                <span class="text">Torna indietro</span>
            </a>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <span class="fas fa-info-circle"></span>
                            Dettagli
                        </h6>
                    </div>
                    <div class="card-body">
                        <p><span class="fas fa-hashtag"></span> <strong>ID:</strong> #{{$user->userId}}</p>
                        <p><span class="fas fa-signature"></span> <strong>Nome e cognome:</strong> {{$user->name . ' ' . $user->surname}}</p>
                        <p><span class="fas fa-user-tag"></span> <strong>Ruolo:</strong> {{$user->getRole()}}</p>
                        <p><span class="fas fa-building"></span> <strong>Ente:</strong> {{$entity->name??'-'}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <span class="fas fa-address-book"></span>
                            Contatti
                        </h6>
                    </div>
                    <div class="card-body">
                        <p><span class="fas fa-envelope text-gray-500"></span> <strong>Email:</strong> {{$user->email}}</p>
                        <p><span class="fab fa-telegram text-primary"></span> <strong>Username Telegram:</strong> {{$user->telegramName??'-'}}</p>
                        <p><span class="fas fa-check text-success"></span> <strong>Conferma account Telegram:</strong> {{$user->telegramChat?'Sì':'No'}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <span class="fas fa-lock"></span>
                            Sicurezza
                        </h6>
                    </div>
                    <div class="card-body">
                        <p><span class="fas fa-shield-alt text-info"></span> <strong>Sicurezza account:</strong>
                            @if($user->tfa)
                                <span class="badge badge-success">Attivo</span>
                            @else
                                <span class="badge badge-danger">Disattivo</span>
                            @endif</p>
                        <p><span class="fas fa-user-lock"></span> <strong>Status account:</strong>
                            @if($user->deleted)
                                <span class="badge badge-danger">Disattivo</span>
                            @else
                                <span class="badge badge-success">Attivo</span>
                            @endif </p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-secondary">
                            <span class="fas fa-user-edit"></span>
                            Opzioni
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($user->type < Auth::user()->type)
                            <a href="{{route('users.edit', $user->userId)}}" class="btn btn-warning btn-icon-split">
                                <span class="icon text-white-50">
                                  <span class="fas fa-user-edit"></span>
                                </span>
                                <span class="text">Modifica</span>
                            </a>
                            @can(['isAdmin'])
                                <a class="btn btn-primary btn-icon-split" href="{{ route('users.reset', ['userId' => $user->userId ]) }}"
                                   onclick="event.preventDefault(); document.getElementById('reset-form-{{$user->userId}}').submit();">
                                    <span class="icon text-white-50">
                                      <span class="fas fa-lock"></span>
                                    </span>
                                    <span class="text">Reset password</span>
                                </a>
                                <form id="reset-form-{{$user->userId}}" action="{{ route('users.reset', ['userId' => $user->userId ]) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('PUT')
                                </form>
                            @endcan
                            @if($user->deleted )
                                <a class="btn btn-success btn-icon-split" href="{{ route('users.restore', ['userId' => $user->userId ]) }}"
                                   onclick="event.preventDefault(); document.getElementById('restore-form-{{$user->userId}}').submit();">
                                <span class="icon text-white-50">
                                  <span class="fas fa-user-check"></span>
                                </span>
                                    <span class="text">Ripristina</span>
                                </a>
                                <form id="restore-form-{{$user->userId}}" action="{{ route('users.restore', ['userId' => $user->userId ]) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('PUT')
                                </form>
                            @else
                                <a class="btn btn-danger btn-icon-split" href="{{ route('users.destroy', ['userId' => $user->userId ]) }}"
                                   onclick="event.preventDefault(); document.getElementById('delete-form-{{$user->userId}}').submit();">
                                <span class="icon text-white-50">
                                  <span class="fas fa-user-times"></span>
                                </span>
                                    <span class="text">Disattiva utente</span>
                                </a>
                                <form id="delete-form-{{$user->userId}}" action="{{ route('users.destroy', ['userId' => $user->userId ]) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        @else
                            Non ci sono opzioni disponibili
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
