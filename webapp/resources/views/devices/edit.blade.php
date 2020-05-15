@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('devices.edit', $device->deviceId))
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex mb-4">
            <h1 class="h3 mb-0 text-gray-800"> Modifica dispositivo</h1>
        </div>

        <div class="d-inline-block my-2 px-0">
            <a href="{{route('devices.index')}}" class="btn btn-sm btn-danger btn-icon-split">
                <span class="icon text-white-50">
                    <span class="fas fa-arrow-circle-left"></span>
                </span>
                <span class="text">Torna indietro</span>
            </a>
        </div>

        <div class="card shadow mt-2 mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                   <span class="icon text-blue-50">
                          <span class="fas fa-edit"></span>
                   </span>
                    Modifica dispositivo
                </h6>
            </div>
                <div id="cardDispositivo" class="card-body">
                    <div class="alert alert-warning"><span class="fas fa-exclamation-triangle"></span>
                        A seguito della modifica di un dispositivo è necessario inviare la <strong>nuova configurazione</strong> al gateway!
                        Ricordati di farlo dalla <a href="{{route('gateways.index')}}">gestione gateways</a>.
                    </div>
                    <p>Puoi modificare un dispositivo inserendo le informazioni elencate in seguito:</p>
                    <form method="POST" action="{{route('devices.update', $device->deviceId)}}" id="update">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="inputDeviceId" class="col-sm-3 col-form-label"><span class="real-id"></span> ID dispositivo</label>
                            <div class="col-sm-9">
                                <input required type="number" class="form-control @error('realDeviceId') is-invalid @enderror" id="inputDeviceId" placeholder="Id dispositivo" value="{{old('realDeviceId')??$device->realDeviceId}}" name="realDeviceId">
                                @error('realDeviceId')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputDeviceName" class="col-sm-3 col-form-label"><span class="fas fa-tag"></span> Nome dispositivo</label>
                            <div class="col-sm-9">
                                <input required type="text" class="form-control @error('name') is-invalid @enderror" id="inputDeviceName" placeholder="Nome dispositivo" value="{{old('name')??$device->name}}" name="name">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputGatewayName" class="col-sm-3 col-form-label"><span class="fas fa-dungeon"></span> Seleziona gateway</label>
                            <div class="col-sm-9">
                                <div class="input-group mb-3">
                                    <select required class="form-control @error('gatewayId') is-invalid @enderror" name="gatewayId" id="inputgatewayName">
                                        @foreach($gateways as $gateway)
                                            <option @if($device->gateway == $gateway->gatewayId) selected @endif  value="{{$gateway->gatewayId}}">{{$gateway->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('gatewayId')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputFrequency" class="col-sm-3 col-form-label"><span class="fas fa-history"></span> Frequenza ricezione dati</label>
                            <div class="col-sm-9">
                                <div class="input-group mb-3">
                                    <select required class="form-control @error('frequency') is-invalid @enderror" name="frequency" id="inputFrequency">
                                        <option @if($device->frequency=='1') selected @endif value="1">1s</option>
                                        <option @if($device->frequency=='2') selected @endif value="2">2s</option>
                                        <option @if($device->frequency=='3') selected @endif value="3">3s</option>
                                        <option @if($device->frequency=='4') selected @endif value="4">4s</option>
                                        <option @if($device->frequency=='5') selected @endif value="5">5s</option>
                                    </select>
                                    @error('frequency')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <span class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">Secondi</span>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div id="sensorsList">
                            @foreach($sensors as $sensor)
                                <div id="sensore{{$sensor->realSensorId}}" class="form-group row">
                                    <label class="col-lg-3 col-form-label">
                                        <span class="fas fa-thermometer-half mx-1"></span>Sensore <span class="real-id">{{$sensor->realSensorId}}</span>
                                    </label>
                                    <label class="col-lg-1 col-form-label">
                                        <span class="real-id"></span> ID
                                    </label>
                                    <div class="col-lg-1">
                                        <input type="text" class="form-control" placeholder="Id sensore" readonly="readonly" value="{{$sensor->realSensorId}}" name="sensorId[]">
                                    </div>
                                    <label class="col-lg-1 col-form-label">
                                        <span class="fas fa-tape mx-1"></span> Tipo
                                    </label>
                                    <div class="col-lg-2">
                                        <input type="text" class="form-control" placeholder="Tipo di sensore" required value="{{$sensor->type}}" name="sensorType[]">
                                    </div>
                                    <label class="col-lg-1 col-form-label">
                                        <span class="fas fa-satellite-dish mx-1"></span> CMD
                                    </label>
                                    <div class="col-lg-2">
                                        <select class="form-control" name="enableCmd[]">
                                            <option selected value="{{$sensor->cmdEnabled?'true':'false'}}">@if($sensor->cmdEnabled===true)Abilitato @else Disabilitato @endif</option>
                                            <option value="{{!$sensor->cmdEnabled?'true':'false'}}">@if($sensor->cmdEnabled===false)Abilitato @else Disabilitato @endif</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-1 col-form-label d-none d-lg-block text-center">
                                        <button class="btn btn-sm btn-danger delete">
                                            <span class="fas fa-trash"></span>
                                        </button>
                                    </div>
                                    <div class="col-lg-1 mt-2 d-lg-none my-1">
                                        <button class="btn btn-sm btn-danger btn-icon-split delete">
                                            <span class="fas fa-trash icon text-white-50"></span>
                                            <span class="text">Elimina sensore</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </form>
                    <hr>
                    <div class="d-inline-block my-2 px-0 float-left">
                        <a onclick="event.preventDefault();
                         return confirm('Sei proprio sicuro di voler cancellare questo dispositivo?') ? document.getElementById('delete').submit() : false;" class="btn btn-danger btn-icon-split" href="#">
                                        <span class="icon text-white-50">
                                          <span class="fas fa-trash"></span>
                                        </span>
                            <span class="text">Elimina dispositivo</span>
                        </a>
                        <form id="delete" action="{{ route('devices.destroy', ['deviceId' => $device->deviceId]) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                   <span class="icon text-blue-50">
                          <span class="fas fa-plus"></span>
                   </span>
                    Aggiunta sensore
                </h6>
            </div>
                <div id="cardSensore" class="card-body">
                    <p>Puoi aggiungere un nuovo sensore inserendo le informazioni elencate in seguito:</p>
                    <form method="POST" id="sensorForm">
                        <div class="form-group row">
                            <label for="inputSensorId" class="col-sm-3 col-form-label"><span class="real-id"></span> ID sensore</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control @error('sensorId') is-invalid @enderror" id="inputSensorId" placeholder="Id sensore" value="" name="sensorId[]">
                                @error('sensorId')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputSensorType" class="col-sm-3 col-form-label"><span class="fas fa-tape"></span> Tipologia</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('sensorType') is-invalid @enderror" id="inputSensorType" placeholder="Tipo di sensore" value="" name="sensorType[]">
                                @error('sensorType')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="commandCheck" class="col-sm-3 col-form-label"><span class="fas fa-satellite-dish"></span> Ricezione comandi *</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="commandCheck">
                                    <option value="true">Abilitato</option>
                                    <option value="false" selected="selected" >Disabilitato</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <p class="my-2 small"><span class="fas fa-info-circle text-primary"></span>
                            *Per inviare un comando tramite Telegram è necessario aver inserito il proprio username Telegram
                            all'interno delle impostazioni ed aver attivato il bot con i comandi <code>/start</code> e <code>/login</code>.
                            Per maggiori informazioni consultare il manuale utente.
                        </p>
                        <hr>
                        <div class="form-group row mx-1 float-right">
                            <button id="addSensor" type="submit" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                  <span class="fas fa-plus-circle"></span>
                                </span>
                                <span class="text">Aggiungi sensore</span>
                            </button>
                        </div>
                    </form>
                </div>
        </div>
        <div class="d-sm-flex mb-4 ml-sm-auto">
            <button type="submit" class="btn btn-success btn-icon-split" form="update" onclick="
                         return confirm('Confermi le modifiche al dispositivo? I sensori eliminati rimuoveranno anche gli alert e grafici view dal sistema.');">
                        <span class="icon text-white-50">
                          <span class="fas fa-save"></span>
                        </span>
                <span class="text">Salva modifiche</span>
            </button>
        </div>
    </div>
@endsection
