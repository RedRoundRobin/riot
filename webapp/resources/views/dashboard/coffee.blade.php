@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('dashboard.coffee'))
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex mb-4">
        <h1 class="h3 mb-0 text-gray-800">Errore 418 - I'm a teapot</h1>
    </div>
    <div class="row">
        <div class="col-auto mb-4">
            <a href="{{route('dashboard.index')}}" class="btn btn-sm btn-danger btn-icon-split">
                <span class="icon text-white-50">
                  <span class="fas fa-arrow-circle-left"></span>
                </span>
                <span class="text">Torna indietro</span>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 mb-1">
            <div class="card shadow mb-2">
                <div class="card-body text-center">
                    We have no more coffee. Please, take a cup of tea
                    <a href="https://github.com/RedRoundRobin" rel="noopener noreferrer" target="_blank">with us</a>
                        <span class="far fa-smile-wink"></span>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <img class="img-fluid" src="images/418.png" alt="errore 418">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
