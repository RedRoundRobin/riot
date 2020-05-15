@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('entities.create'))
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800"> Creazione ente</h1>
        </div>
        <div class="row">
            <div class="col-auto mb-4 ">
                <a href="{{route('entities.index')}}" class="btn btn-sm btn-danger btn-icon-split">
                        <span class="icon text-white-50">
                          <span class="fas fa-arrow-circle-left"></span>
                        </span>
                    <span class="text">Torna indietro</span>
                </a>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                   <span class="icon text-blue-50">
                          <span class="fas fa-plus-circle"></span>
                   </span>
                    Creazione ente
                </h6>
            </div>
            @can(['isAdmin'])
                <div id="cardGateway" class="card-body">
                    <p>Puoi creare un nuovo ente inserendo le informazioni elencate in seguito:</p>
                    <form method="POST" action="{{route('entities.store')}}" id="create">
                        @csrf
                        @method('POST')
                        <div class="form-group row">
                            <label for="inputEntityName" class="col-sm-4 col-form-label"><span class="fas fa-building"></span> Nome ente</label>
                            <div class="col-sm-8">
                                <input required type="text" class="form-control @error('name') is-invalid @enderror" id="inputEntityName" placeholder="Nome dell'ente" value="{{old('name')}}" name="name">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEntityLocation" class="col-sm-4 col-form-label"><span class="fas fa-location-arrow"></span> Luogo </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('location') is-invalid @enderror" id="inputEntityLocation" placeholder="Sede dell'ente" value="{{old('location')}}" name="location">
                                @error('location')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                        </div>
                    </form>
                    <hr class="my-4">
                    <button type="submit" class="btn btn-primary btn-icon-split" form="create">
                        <span class="icon text-white-50">
                          <span class="fas fa-plus-circle"></span>
                        </span>
                        <span class="text">Conferma aggiunta</span>
                    </button>
                    @endcan
                </div>
        </div>
    </div>


@endsection
