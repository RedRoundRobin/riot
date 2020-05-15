@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('views.index'))
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Pagine view </h1>
    </div>
    @include('layouts.error')
    <div class="d-sm-flex mb-4 ml-sm-auto">
        <a href="{{route('dashboard.index')}}" class="btn btn-sm btn-danger btn-icon-split">
            <span class="icon text-white-50">
              <span class="fas fa-arrow-circle-left"></span>
            </span>
            <span class="text">Torna indietro</span>
        </a>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <span class="fas fa-plus-circle"></span>
                        Creazione pagina view
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('views.store')}}">
                        @csrf
                        @method('POST')
                        <div class="form-group row">
                            <label for="inputViewName" class="col-sm-4 col-form-label"><span class="fas fa-tag"></span> Nome view </label>
                            <div class="col-sm-8">
                                <input required type="text" class="form-control @error('viewName') is-invalid @enderror" id="inputViewName" placeholder="Nome della pagina view" value="{{old('viewName')}}" name="viewName">
                                @error('viewName')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>

                    <div class="d-sm-flex mb-4 ml-sm-auto">
                        <button type="submit" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <span class="fas fa-plus-circle"></span>
                                </span>
                            <span class="text">Aggiungi</span>
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <span class="fas fa-info-circle"></span>
                        Informazioni sulle pagine view
                    </h6>
                </div>
                <div class="card-body">
                    <p>Attraverso le pagine view è possibile aggiungere dei <strong>grafici personalizzati</strong> eseguendo il
                        tracciamento dati di due sensori differenti. È possibile selezionare anche un'eventuale correlazione dei due dati tracciati, tra cui:</p>
                    <ul>
                        <li>Covarianza</li>
                        <li>Correlazione di Pearson</li>
                        <li>Correlazione di Spearman</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach($views as $view)
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-{{$view->viewId%2?"info":"primary"}} shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold text-secondary text-uppercase mb-2">{{$view->name}}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <a href="{{route('views.show', ['viewId' => $view->viewId])}}" class="btn btn-{{$view->viewId%2?"info":"primary"}}">Visualizza view</a>
                                </div>
                            </div>
                            <div class="col-auto">
                                <span class="far fa-chart-bar fa-2x text-gray-500"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
