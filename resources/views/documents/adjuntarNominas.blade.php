@extends('layouts.app')

@section('content')
<style>
    .tarjeta{
      border: 1px solid #E2870C;
      min-height: 280px;
      min-width: 420px;
      height: auto;
      width: auto;
      border-top-right-radius: 50px;
      border-bottom-left-radius: 50px;
      float:left;
      transition: all .3s ease-in-out;
    }

    .center{
        display: flex;
        justify-content: center;
    }

    .titulo{
        display: flex;
        justify-content: center;
        padding: 20px;
        border-top-right-radius: 50px;
    }

    .cuerpo{
        padding: 10px;
        margin: 10px;
    }

    .pop_alt{
        padding:10px;
        margin:10px;
        border: 1px solid #E2870C;
        border-radius: 30px;
        background-color:#F9A958;
    }

    .pop_alt:hover{
        background-color:white;
        transform: scale(1.1);
        transition: all .3s ease-in-out;
    }

    .enlace_alt{
        color:white;
    }

    .enlace_alt:hover{
        color:#E2870C;
        text-decoration:none;
    }
</style>

<form method="POST" action="{{ route('nominas') }}" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="center">
        <button type="submit" style="padding:5px;height:40px;border-radius:30px;border: 1px solid #E2870C;color:#E2870C">
            {{ __('Subir') }}
        </button>
    </div>
    <div class="center">
        <div class="pop_alt">
            <a href="{{ route('documents.index') }}" class="enlace_alt">Atrás</a>
        </div>
    </div>
</form>


@endsection