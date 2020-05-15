@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <div class="mb-3 d-lg-none">
                                        <span class="fas fa-project-diagram thirema-logo-icon"></span>
                                        <span class="thirema-logo-special-font thirema-logo-color-blue">r</span>
                                        <span class="thirema-logo-special-font">iot</span>
                                    </div>
                                    <h1 class="h4 text-gray-900 mb-4">Codice TFA <span class="fab fa-telegram text-primary"></span> </h1>
                                    <p>Accedi a <strong>Telegram</strong> e inserisci il codice che ti è stato inviato entro 5 minuti.</p>
                                </div>
                                <form class="user" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input id="code" type="text" required
                                               class="form-control form-control-user form-codice-tfa @error('code') is-invalid @enderror"
                                               name="code" placeholder="Inserisci il codice" autocomplete="off">

                                        @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Invia codice <span class="fas fa-sign-in-alt"></span>
                                        </button>
                                    </div>
                                </form>
                                <div class="mt-4">
                                    <span class="fas fa-arrow-circle-left"></span>
                                    <a href="{{ route('login') }}" class="text-secondary">Torna al login</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
