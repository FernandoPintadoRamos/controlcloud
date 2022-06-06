@extends('layouts.app')

@section('content')

<style>
    .center{
        display: flex;
        justify-content: center;
    }

    .tarjeta{
      border: 1px solid #E2870C;
      min-height: 280px;
      min-width: 370px;
      height: auto;
      width: auto;
      border-top-right-radius: 50px;
      border-bottom-left-radius: 50px;
      float:left;
      transition: all .3s ease-in-out;
    }

    .cuerpo{
        margin: 20px;
        height: auto;
        width: auto;
    }

    .titulo{
        display: flex;
        justify-content: center;
        padding: 20px;
        border-top-right-radius: 50px;
    }
</style>

<div class="center" >
    <div class="tarjeta">
        <div class="titulo" style="background-color:#F7AB40">
            <strong>Inicio de sesion</strong>
        </div>
        <div class="cuerpo">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                    Direccion mail</br>

                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    Contraseña

                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old("remember") ? "checked" : "" }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Recuérdame') }}
                            </label>
                        </div>

                <div class="center">
                    <button type="submit" class="btn btn-secondary">
                        {{ __('Iniciar sesión') }}
                    </button>
                </div>
                <div class="center">
                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('¿Has olvidado tu contraseña?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>    
</div>
@endsection
