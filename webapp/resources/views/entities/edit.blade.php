@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('entities.edit', $entity->entityId))
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800"> Modifica ente</h1>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{route('entities.index')}}" class="btn btn-sm btn-danger btn-icon-split">
                    <span class="icon text-white-50">
                      <span class="fas fa-arrow-circle-left"></span>
                    </span>
                <span class="text">Torna indietro</span>
            </a>
            @if($entity->deleted !== true)
                <a href="#" onclick="event.preventDefault();
                 return confirm('Sei proprio sicuro di voler cancellare questo ente?') ? document.getElementById('delete').submit() : false;" class="btn btn-sm btn-danger btn-icon-split">
                    <span class="icon text-white-50">
                      <span class="fas fa-trash"></span>
                    </span>
                    <span class="text">Elimina ente</span>
                </a>
                <form id="delete" action="{{ route('entities.destroy', ['entityId' => $entity->entityId]) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                           <span class="icon text-blue-50">
                                  <span class="fas fa-plus-circle"></span>
                           </span>
                            Modifica ente
                        </h6>
                    </div>
                    <div id="cardGateway" class="card-body">
                        <p>Puoi modificare un ente esistendo inserendo le informazioni elencate in seguito:</p>
                        <form method="POST" action="{{route('entities.update', $entity->entityId)}}" id="update">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label for="inputEntityName" class="col-sm-3 col-form-label"><span class="fas fa-building"></span> Nome ente</label>
                                <div class="col-sm-9">
                                    <input required type="text" class="form-control @error('name') is-invalid @enderror" id="inputEntityName" placeholder="Nome dell'ente" value="{{old('name')??$entity->name}}" name="name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEntityLocation" class="col-sm-3 col-form-label"><span class="fas fa-location-arrow"></span> Luogo </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" id="inputEntityLocation" placeholder="Sede dell'ente" value="{{old('location')??$entity->location}}" name="location">
                                    @error('location')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>
                            <hr class="my-4">
                        </form>
                        <div class="my-1 px-0">
                            <button type="submit" class="btn btn-success btn-icon-split" form="update">
                                <span class="icon text-white-50">
                                  <span class="fas fa-save"></span>
                                </span>
                                <span class="text">Salva modifiche</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

