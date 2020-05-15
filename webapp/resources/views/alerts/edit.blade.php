@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('alerts.edit', $alert->alertId))
@section('content')

<div class="container-fluid">
    <div class="d-sm-flex mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Modifica alert</h1>
    </div>
    <div class="d-flex justify-content-between">
        <a href="{{route('alerts.index')}}" class="btn btn-sm btn-danger btn-icon-split mb-3">
        <span class="icon text-white-50">
          <span class="fas fa-arrow-circle-left"></span>
        </span>
            <span class="text">Torna indietro</span>
        </a>
        <a class="btn btn-sm btn-danger btn-icon-split mb-3" href="{{ route('alerts.destroy', ['alertId'=>$alert->alertId]) }}"
           onclick="event.preventDefault();
            return confirm('Sei proprio sicuro di voler cancellare questo alert?') ? document.getElementById('destroy-view').submit() : false;">
            <span class="icon text-white-50">
              <span class="fas fa-trash-alt"></span>
            </span>
            <span class="text">Elimina alert</span>
        </a>
        <form id="destroy-view" action="{{ route('alerts.destroy', ['alertId'=>$alert->alertId]) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><span class="fas fa-bell"></span> Modifica alerts</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive-sm">
                        <form action="{{route('alerts.update', ['alertId'=>$alert->alertId])}}" method="POST">
                            @csrf
                            @method('PUT')
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
                                            <option value="0" @if($alert->type == 0) selected @endif>maggiore di</option>
                                            <option value="1" @if($alert->type == 1) selected @endif>minore di</option>
                                            <option value="2" @if($alert->type == 2) selected @endif>uguale a</option>
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
                                        <input required type="number" step="0.1" class="form-control @error('threshold') is-invalid @enderror" name="threshold" id="inputValue"
                                               placeholder="Inserisci un valore di soglia" value="{{$alert->threshold}}">
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
                      <span class="fas fa-save"></span>
                    </span>
                                <span class="text">Salva modifiche</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
