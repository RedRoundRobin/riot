@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('logs.index'))
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Logs</h1>
    </div>
    <div class="row">
        <div class="col-auto mb-4 ">
            <a href="{{route('dashboard.index')}}" class="btn btn-sm btn-danger btn-icon-split">
                <span class="icon text-white-50">
                  <span class="fas fa-arrow-circle-left"></span>
                </span>
                <span class="text">Torna indietro</span>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-list-alt"></span> Lista Logs</h6>
                </div>
                <div class="card-body">
                    @can(['isMod']) <div class="alert alert-info"><span class="fas fa-info-circle"></span> Si stanno visualizzando le logs di tutti gli utenti del proprio ente.</div> @endcan
                    @can(['isAdmin']) <div class="alert alert-info"><span class="fas fa-info-circle"></span> Si stanno visualizzando le logs di tutti gli utenti registrati a sistema.</div> @endcan
                    <div class="table-responsive-lg">
                        <table class="table table-striped table-bordered border-secondary">
                            <thead class="thead-dark table-borderless">
                            <tr>
                                <th>Data ora</th>
                                <th>Azione</th>
                                <th>Target</th>
                                <th>Nome e cognome</th>
                                <th>Rango</th>
                                <th>Indirizzo IP</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $l)
                                    <tr>
                                        <td class="small">{{$l["log"]->time}}</td>
                                        <td><code>{{$l["log"]->operation}}</code></td>
                                        <td><code>{{$l["log"]->data}}</code></td>
                                        <td><a href="{{route('users.show', ['userId' => $l['user']->userId])}}">{{$l['user']->name. ' ' .$l['user']->surname}}</a></td>
                                        <td>{{$l['user']->getRole()}}</td>
                                        <td><code>{{$l["log"]->ipAddr}}</code></td>
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
