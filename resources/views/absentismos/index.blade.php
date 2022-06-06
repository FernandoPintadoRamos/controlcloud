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
        color:black;
    }

    .enlace_alt:hover{
        color:black;
        text-decoration:none;
    }
</style>

<script>
</script>

<body>
    @if(Auth::user()->role == 'supervisor')
        <div class="center">
            <a href="{{route('verAbsentismoAdmin')}}">
                <div class="tarjeta">
                    <div style="min-height:160px">
                        <strong style="color:black">Ver solicitudes</strong> <br>
                        <p style="color:black">Ver solicitudes que se han realizado.</p>
                    </div>
                </div>
            </a>

            
            <a href="{{route('asignarFaltas')}}">
                <div class="tarjeta">
                    <div style="min-height:160px">
                        <strong style="color:black">Asignar absentismos</strong> <br>
                        <p style="color:black">Asignar faltas, permisos, etc a empleados</p>
                    </div>
                </div>
            </a>
        </div>
    @else
        <div class="center">
            <a href="{{route('solicitarAbsentismo')}}">
                <div class="tarjeta">
                    <div style="min-height:160px">
                        <strong style="color:black">Solicitar absentismo</strong> </br>
                        <p style="color:black">Solicita Permisos, Faltas, Partes, etc...</p>
                    </div>
                </div>
            </a>
            
            <a href="{{route('verAbsentismo')}}">
                <div class="tarjeta">
                    <div style="min-height:160px">
                        <strong style="color:black">Ver solicitudes</strong> </br>
                        <p style="color:black">Vea las solicitudes que ha realizado.</p>
                    </div>
                </div>
            </a>
        </div>
    @endif
    <br>
    <div class="center">
        <a href="{{ route('users.index')}}" class="enlace_alt">
          <div class="pop_alt">
            Volver al Menú
          </div>
        </a>
      </div>
</body>

@endsection