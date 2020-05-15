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
                                    <h1 class="h4 text-gray-900 mb-4">Accesso Webapp</h1>
                                </div>
                                <form class="user" method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="form-group">
                                            <input id="email" type="email" required
                                                   class="form-control form-control-user @error('email') is-invalid @enderror"
                                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                                    placeholder="Indirizzo Email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                    </div>

                                    <div class="form-group">
                                        <input id="password" type="password" required
                                               class="form-control form-control-user @error('password') is-invalid @enderror"
                                               name="password" required autocomplete="current-password"
                                               placeholder="Password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Accedi <span class="fas fa-sign-in-alt"></span>
                                        </button>

                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    </div>
                                </form>
                                <hr>
                                <div class="text-center small">
                                    <span class="fas fa-lock"></span> <strong>Password dimenticata?</strong> Contatta il supporto tecnico!
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
