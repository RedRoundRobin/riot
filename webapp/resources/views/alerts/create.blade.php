@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('alerts.create'))
@section('content')

<div class="container-fluid">
    <div class="d-sm-flex mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Crea alert</h1>
    </div>
    <div class="row">
        <div class="col-auto mb-4 ">
            <a href="{{route('alerts.index')}}" class="btn btn-sm btn-danger btn-icon-split">
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
                    <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-bell"></span> Creazione alert</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive-sm">
                        <form action="{{route('alerts.store')}}" method="POST">
                            @csrf
                            @method('POST')

                            <div class="form-group row">
                                <label for="inputSensore" class="col-sm-3 col-form-label"><span class="fas fa-temperature-high"></span> Sensore</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <select required class="form-control @error('sensor') is-invalid @enderror" name="sensor" id="inputSensor">
                                            @foreach($devices as $d)
                                                @foreach($sensors[$d->deviceId] as $s)
                                                    <option value="{{$s->sensorId}}">{{$s->type.' S@' . $s->realSensorId.' // '.$d->name . ' D#'.$s->device }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                        @error('sensor')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputSoglia" class="col-sm-3 col-form-label"><span class="fas fa-radiation"></span> Soglia</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <select required class="form-control @error('type') is-invalid @enderror" name="type" id="inputSoglia">
                                            <option value="0" selected>maggiore di</option>
                                            <option value="1">minore di</option>
                                            <option value="2">uguale a</option>
                                        </select>
                                        @error('type')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputValue" class="col-sm-3 col-form-label"><span class="fas fa-radiation-alt"></span> Valore di soglia</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <input type="number" step="0.1" required class="form-control @error('threshold') is-invalid @enderror" name="threshold" id="inputValue"
                                               placeholder="Inserisci un valore di soglia" value="valorenumerico">
                                        @error('threshold')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                      <span class="fas fa-plus-circle"></span>
                    </span>
                                <span class="text">Crea alert</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
