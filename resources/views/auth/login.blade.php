@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                {{-- @error('email')
                                    @if (str_contains($message, 'verify your email'))
                                        <div class="alert alert-warning d-flex align-items-center mt-2" role="alert">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {!! $message !!}
                                            </div>
                                        </div>

                                        <form id="resend-verification-form" method="POST" action="{{ route('verification.send') }}" class="d-none">
                                            @csrf
                                        </form>
                                    @else
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @endif
                                @enderror --}}
                                @error('email')
                                    @if (str_contains($message, 'verify your email'))
                                        <div class="alert alert-warning d-flex align-items-center mt-2" role="alert">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                <p class="mb-2">{!! $message !!}</p>

                                                <!-- Bouton qui déclenche le JavaScript -->
                                                <button type="button" class="btn btn-sm btn-warning" onclick="resendVerification()">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    Resend verification email
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Formulaire caché avec l'ID correct -->
                                        <form id="resend-verification-form" method="POST" action="{{ route('verification.resend') }}" style="display: none;">
                                            @csrf
                                        </form>

                                        <script>
                                        function resendVerification() {
                                            // Vérifier que l'élément existe avant de le soumettre
                                            const form = document.getElementById('resend-verification-form');
                                            if (form) {
                                                if (confirm('Voulez-vous recevoir un nouvel email de vérification ?')) {
                                                    form.submit();
                                                }
                                            } else {
                                                console.error('Formulaire de renvoi non trouvé');
                                                alert('Erreur technique. Rechargez la page.');
                                            }
                                        }
                                        </script>
                                    @else
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @endif
                                @enderror

                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
