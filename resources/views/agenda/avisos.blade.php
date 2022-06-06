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
        color:#E2870C;
    }

    .enlace:hover{
        color: white;
        text-decoration:none;
    }

    .enlace_alt{
        color:white;
    }

    .enlace_alt:hover{
        color:#E2870C;
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
</style>
<?php
    use App\User;
?>
<div class="center">
    <div class="tarjeta">
        <div class="titulo" style="background-color:#F7AB40">
        <h5><strong>Avisos</strong></h5>
        </div>
        <div class="center">
            <a href="{{route('verTodos')}}" class="enlace">
                <div class="pop">
                    <p class="enlace">Ver todos</p>
                </div>
            </a>

            <a href="{{route('verLeidos')}}" class="enlace">
                <div class="pop">
                    <p class="enlace">Ver leidos</p>
                </div>
            </a>

            <a href="{{route('avisos')}}" class="enlace">
                <div class="pop">
                    <p class="enlace">Ver no leidos</p>
                </div>
            </a>
        </div>

        <div class="center">
            <div style="overflow:auto">
        <table id="myTable">
            <thead>
                <tr style="background-color:#F7AD62">
                    <td>Nombre<br>empleado</td>
                    <td>Codigo<br>empleado</td>
                    <td>Empresa<br>empleado</td>
                    <td>CIF<br>empresa</td>
                    <td>Asunto</td>
                    <td>Contenido</td>
                    <td>Fecha</td>
                    <td>Hora</td>
                    <td>Ver archivo</td>
                    <td>Marcar<br>leido</td>
                </tr>
            </thead>

            <tbody>
                @foreach($avisos as $aviso)
                    <?php
                        $usuario = User::find($aviso['usuario_transmisor']);
                    ?>
                    <tr>
                        <td>{{$usuario->NOM}}</td>
                        <td>{{$usuario->COD}}</td>
                        <td>{{$usuario->EMP}}</td>
                        <td>{{$usuario->CIF}}</td>
                        <td>{{$aviso['asunto']}}</td>
                        <td>{{$aviso['contenido']}}</td>
                        <td>{{$aviso['fecha']}}</td>
                        <td>{{$aviso['hora']}}</td>
                        <td><a href="{{route('verArchivo', ['id_archivo'=>$aviso['id_archivo']])}}" class="enlace_descarga">Ver archivo</a></td>
                        @if($aviso['visto'])
                            <td><a href="{{route('marcarNoLeido', ['id_aviso'=>$aviso['id']])}}">
                                <img height=30px width=30px src="{{asset('/featureds/ojo.png')}}">
                            </a></td>
                        @else
                            <td><a href="{{route('marcarLeido', ['id_aviso'=>$aviso['id']])}}">
                                <img height=30px width=30px src="{{asset('/featureds/ver.png')}}">
                            </a></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
            </tfoot>
        </table>
        </div>
        </div>
        <div class="center">
            <div class="pop_alt">
                <a href="{{ route('documents.index')}}" class="enlace_alt">Volver al Menú</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      
    });
  });
</script>
@endsection