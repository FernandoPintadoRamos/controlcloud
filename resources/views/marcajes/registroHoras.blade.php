@extends('layouts.app')
@section('content')

<style>
    .uper {
      margin-top: 50px;
    }


    .principal{
        background-color:#F7AD62;
    }

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

    .pop{
        padding:10px;
        margin:10px;
        border: 1px solid #E2870C;
        border-radius: 30px;
        transition: all .3s ease-in-out;
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
        transition: all .3s ease-in-out;
    }

    .enlace:hover{
        color: white;
        text-decoration:none;
        transition: all .3s ease-in-out;
    }

    .enlace_alt{
        color:white;
    }

    .enlace_alt:hover{
        color:black;
        text-decoration:none;
    }

    .cuerpo{
        padding: 10px;
        margin: 10px;
    }

    .cuerpo div{
        display: flex;
    }

    table{
        border: 1px solid #F7942F;
    }

    th, td{
        padding: 3px;
        border: 1px solid #F7942F;
    }
</style>

<?php
    $miuseragent=$_SERVER['HTTP_USER_AGENT'];
    $posibles = ["Mobile", "iPhone", "iPod", "BlackBerry", "Opera mini", "Sony", "MOT", "Nokia", "samsung"];
    $movil = false;

    foreach($posibles as $posible){
        if(str_contains($miuseragent, $posible)){
            $movil = true;
        }
    }
?>

<?php
    use App\User;             
?>

<!-- Mensaje de alerta -->
<div class="container uper">
    @if(isset($success))
        <div class="alert alert-success">
        {{ $success }}  
        </div><br />
    @endif
</div>
<!--Fin Mensaje de alerta-->

<div class="center">
    <div class="tarjeta">
        <div class="titulo" style="background-color:#F7AB40">
        <h5><strong>Registro de horas</strong></h5>
        </div>
        @if($movil)
        <div style="padding:0px">
        @else
        <div style="padding:10px">
        @endif
            <div style="overflow: auto">
            <table id="myTable" class="table table-striped" data-order='[[ 0, "desc" ]]'>
                <thead>
                    <tr class="principal">
                        <th>
                            Productor
                        </th>

                        <th>
                            Nombre completo
                        </th>

                        <th>
                            Empresa
                        </th>

                        <th>
                            Fecha
                        </th>

                        <th>
                            Horas previstas
                        </th>

                        <th>
                            Horas registradas
                        </th>

                        <th>
                            Bolsa de horas
                        </th>

                        <th>
                            Horas compensadas
                        </th>
                    </tr>    
                </thead>
                <tbody>
                    @if($registros!=null)
                        @foreach($registros as $registro)

                            <?php
                                $usuario = User::find($registro->id_worker);
                            ?>
                        <tr>
                            <td>
                                {{$usuario->COD}}
                            </td>
                                
                            <td>
                                {{$usuario->AP1. $usuario->AP2. $usuario->NOM}}
                            </td>

                            <td>
                                {{$usuario->EMP}}
                            </td>

                            <td>
                                {{$registro->fecha_registro}}
                            </td>

                            <td>
                                {{$registro->horas_previstas}}
                            </td>

                            <td>
                                {{$registro->horas_registradas}}
                            </td>

                            <td>
                                {{$registro->bolsa_horas}}
                            </td>

                            <td>
                                {{$registro->horas_compensadas}}
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            </div>
        </div>
        <div class="center">
            <a href="{{ route('users.index')}}" class="enlace_alt">
                <div class="pop_alt">
                    Volver al Menú
                </div>
            </a>
        </div>
    </div>
</div>
<!--
<div style="margin:50px">



</div>
-->
@endsection('content')

@section('script')
<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      
    });
  });
</script>
@endsection