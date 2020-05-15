@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('entities.index'))
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800"> Gestione enti </h1>
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
                    <a href="{{route('entities.create')}}" class="btn btn-sm btn-success btn-icon-split">
                        <span class="icon text-white-50">
                          <span class="fas fa-plus-circle"></span>
                        </span>
                        <span class="text">Aggiungi ente</span>
                    </a>
                </div>
        </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-city"></span> Lista enti</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive-lg">
                        <table class="table table-bordered table-striped border-secondary">
                            <thead class="thead-dark table-borderless">
                            <tr>
                                <th>Nome</th>
                                <th>Luogo</th>
                                <th>Status</th>
                                <th class="bg-secondary" width="100"> </th>
                                <th class="bg-secondary" width="100"> </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($entities as $entity)
                                <tr>
                                    <td><a href="{{route('entities.show', ['entityId' => $entity->entityId ])}}">{{$entity->name}}</a></td>
                                    <td>{{$entity->location}}</td>
                                    <td>@if($entity->deleted===true)
                                            <span class="badge badge-danger">Eliminato</span>
                                        @else
                                            <span class="badge badge-success">Attivo</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('entities.show', ['entityId' => $entity->entityId])}}" class="btn btn-sm btn-info btn-icon-split">
                                            <span class="icon text-white-50">
                                              <span class="fas fa-building"></span>
                                            </span>
                                            <span class="text">Gestisci</span>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('entities.edit', ['entityId' => $entity->entityId])}}" class="btn btn-sm btn-warning btn-icon-split">
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

