@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color:#DC6D10">{{ __('Verifica tu dirección Email') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Un nuevo correo de verificación se ha enviado a la dirección de correo indicada.') }}
                        </div>
                    @endif

                    {{ __('Antes de proceder, por favor, verifique su dirección de correo.') }}
                    <!--{{ route('verification.resend') }}-->
                    {{ __('Si no has recibido el enlace de verificación ') }}, <a href="{{ route('verification.resend') }}">{{ __('haz click aquí para enviar uno nuevo') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
