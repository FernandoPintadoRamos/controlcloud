@extends('layouts.app')

@section('content')
<style>
    .tarjeta{
      border: 1px solid #E2870C;
      min-height: 280px;
      max-width: 400px;
      height: auto;
      width: auto;
      margin: 20px;
      padding: 20px;
      border-top-right-radius: 50px;
      border-bottom-left-radius: 50px;
      float:left;
      transition: all .3s ease-in-out;
    }

    .tarjeta:hover{
      transform: scale(1.1);
      background-color: #F9A958;
      transition: background-color 500ms;
    }

    .center{
        display: flex;
        justify-content: center;
        width:100%;
    }

    .titulo{
        display: flex;
        justify-content: center;
        padding: 20px;
        border-top-right-radius: 50px;
    }

    .pop{
        padding:10px;
        margin:10px;
        border: 1px solid #E2870C;
        border-radius: 30px;
    }

    .pop:hover{
        background-color:#F9A958;
        transform: scale(1.1);
        transition: all .3s ease-in-out;
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

    .enlace{
        color:black;
    }

    .enlace:hover{
        color: white;
        text-decoration:none;
    }

    .enlace_alt{
        color:white;
    }

    .enlace_alt:hover{
        color:black;
        text-decoration:none;
    }

    .enlace_descarga{
        color:#E2870C;
    }

    .enlace_descarga:hover{
        color: #DC7E00;
        text-decoration:none;
    }

    .cuerpo{
        padding: 10px;
        margin: 10px;
    }

    .cuerpo div{
        display: flex;
    }

    .principal{
        background-color:#F7AD62;
    }

    .linea{
        background-color:#FAC896;
    }

    table{
        border: 1px solid #F7942F;
        -moz-border-radius: 20px;
    }

    tbody tr:nth-child(odd) {
      background: #FAC896; 
    }

    th, td{
        padding: 3px;
        border: 1px solid #F7942F;
    }

    .not{
      color:black;
      border: 1px solid #FFB900;
      padding-left: 4px;
      padding-right: 4px;
      border-radius: 50px;
      background-color: #A6F9F2;

      animation: twinkle 0.5s infinite alternate;
    }

    @keyframes twinkle {
            0%{
                opacity:.0.8;
            }
            100%{
                opacity:0;
            }
        }
</style>

<?php
  use App\Agenda;

  $avisos = Agenda::where('usuario_receptor', '=', Auth::user()->id)->where('visto', '=', '0')->get()->toArray();
  $num_avisos = sizeof($avisos);
?>

<div class="center">

      <a href="{{route('avisos')}}" class="enlace">
        <div class="pop">
          Avisos
          @if($num_avisos!=0)
            <big><sup class="not">{{$num_avisos}}</sup></big>
          @endif
        </div>
      </a>
      <a href="{{ route('documents.create')}}" class="enlace">
        <div class="pop">
          Añadir Documentación
        </div>
      </a>

      <!--
      @if(Auth::user()->role=='supervisor')
      <a href="#" data-toggle="modal" data-target="#selectModal" class="enlace">
        <div class="pop">
          Adjuntar Directorio de nóminas
        </div>
      </a>

      <a href="#" data-toggle="modal" data-target="#mostrarNominas" class="enlace">
        <div class="pop">
          Mostrar nóminas
        </div>
      </a>

      <a href="#" data-toggle="modal" data-target="#borrarNominas" class="enlace">
        <div class="pop">
          Borrar lote nóminas
        </div>
      </a>
      @endif
      -->
    </div>
<div class="center">

<a href="{{ route('certificados')}}">
    <div class="tarjeta">
      <div style="float:right">
        <img height=50px width=50px src="{{asset('/featureds/usuario-de-perfil.png')}}">
      </div>
      <div style="min-height:160px">
        <strong style="color:black">Certificados y Documentación</strong> </br>
        <p style="color:black">Todos los certificados y documentos subidos.</p>
      </div>
    </div>
  </a>
  

  <a href="{{ route('verNominas')}}">
    <div class="tarjeta">
      <div style="float:right">
        <img height=50px width=50px src="{{asset('/featureds/usuario-de-perfil.png')}}">
      </div>
      <div style="min-height:160px">
        <strong style="color:black">Nóminas</strong> </br>
        <p style="color:black">Todas las nominas subidas.</p>
      </div>
    </div>
  </a>
  
  </div>
<div class="center">

  <a href="{{route('general')}}">
    <div class="tarjeta">
      <div style="float:right">
        <img height=50px width=50px src="{{asset('/featureds/usuario-de-perfil.png')}}">
      </div>
      <div style="min-height:160px">
        <strong style="color:black">General</strong> </br>
        <p style="color:black">Todos los archivos subidos.</p>
      </div>
    </div>
  </a>
</div>

<div class="center">
  <a href="{{ route('users.index')}}" class="enlace"> 
    <div class="pop">
        Volver al menú
    </div>
  </a>
</div>



<div class="modal fade" id="selectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Subir nóminas.</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">


      <form method="POST" action="{{ route('nominas') }}" enctype='multipart/form-data'>
        {{ csrf_field() }}

        <label for="fichero" class="col-md-4 col-form-label text-md-right">{{ __('Archivo:') }}</label>

                            <div class="col-md-6">
                            <input maxlength="5" type="file" id="fichero" name="fichero" value="{{ old('fichero') }}" required autofocus/>

                              @if ($errors->has('fichero'))
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $errors->first('fichero') }}</strong>
                                  </span>
                              @endif                             
                            </div>
        

        <label for="oculto" class="col-md-4 col-form-label text-md-right">{{ __('Subir ocultas:') }}</label>
        <input id="oculto" type="checkbox" name="oculto" autofocus></input>
        <br>
        <label for="sobrescribir" class="col-md-4 col-form-label text-md-right">{{ __('Sobreescribir nominas:') }}</label>
        <input id="sobrescribir" type="checkbox" name="sobrescribir" autofocus></input>

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

                            
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="mostrarNominas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Subir nóminas.</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">


      <form method="POST" action="{{ route('mostrarNominas') }}" enctype='multipart/form-data'>
        {{ csrf_field() }}

        <label for="fichero" class="col-md-4 col-form-label text-md-right">{{ __('Archivo:') }}</label>

        <div class="col-md-6">
        <input maxlength="5" type="file" id="fichero" name="fichero" value="{{ old('fichero') }}" required autofocus/>

          @if ($errors->has('fichero'))
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('fichero') }}</strong>
              </span>
          @endif                             
        </div>

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

                            
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="borrarNominas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Subir nóminas.</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">


      <form method="POST" action="{{ route('borrarNominas') }}" enctype='multipart/form-data'>
        {{ csrf_field() }}

        <label for="fichero" class="col-md-4 col-form-label text-md-right">{{ __('Archivo:') }}</label>

        <div class="col-md-6">
        <input maxlength="5" type="file" id="fichero" name="fichero" value="{{ old('fichero') }}" required autofocus/>

          @if ($errors->has('fichero'))
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('fichero') }}</strong>
              </span>
          @endif                             
        </div>

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

                            
      </div>
    </div>
  </div>
</div>
@endsection