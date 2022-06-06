@extends('layouts.app')

@section('content')

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

<style>
    .uper {
      margin-top: 50px;
    }

    .center{
        display: flex;
        justify-content: center;
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

    .titulo{
        display: flex;
        justify-content: center;
        padding: 20px;
        border-top-right-radius: 50px;
    }

    #map {
  height: 400px;
  /* The height is 400 pixels */
  width: 100%;
  /* The width is the width of the web page */
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

    .enlace{
        color:black;
    }

    .enlace:hover{
        color: white;
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
        border-radius: 15px;
        -moz-border-radius: 20px;
    }

    th, td{
        padding: 3px;
        border: 1px solid #F7942F;
    }

    .principal{
        background-color:#F7AD62;
    }

    .linea{
        background-color:#FAC896;
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
        color:black;
        text-decoration:none;
    }

    .miTabla td{
        padding: 10px;
    }
    .miTabla th{
        padding: 10px;
    }
</style>

<?php
        use App\Turnos;
        use App\Centro;

        $turno = Turnos::find($centro->horario);
?>

<?php
    $all_users = Centro::all()->toArray();
?>

<script>
    function cargarSelectEmpresa(valor) {
        if(valor==0) {
            // desactivamos el segundo select 
            document.getElementById("selectEmpleado").hidden=true; 
            document.getElementById("selectEmpresa").hidden=true; 
            document.getElementById("labelEmpresa").hidden=true;
            document.getElementById("labelEmpleado").hidden=true;
        }else{
            // eliminamos todos los posibles valores que contenga el selectEmpresa 
            document.getElementById("selectEmpresa").options.length=0; 
            // añadimos los nuevos valores al selectEmpresa 
            var cont = 0;
            var ultima_empresa = [];
            document.getElementById("selectEmpresa").options[cont]=new Option("", "");
            var users = <?php echo json_encode($all_users); ?>;
            for(var i = 0; i < users.length; i++){
              if((users[i]["CIF"])==valor&&(!ultima_empresa.includes((users[i])["EMP"]))){
                cont = cont + 1;
                document.getElementById("selectEmpresa").options[cont]=new Option((users[i]["EMP"]), (users[i]["EMP"]).concat('-', valor));
                ultima_empresa.push((users[i])["EMP"]);
              }
            }
            
            // habilitamos el segundo select 
            document.getElementById("selectEmpresa").hidden=false;
            document.getElementById("labelEmpresa").hidden=false;
      } 
    } 

    function cargarSelectEmpleado(valor) {
        var empresa = valor.split('-')[0];
        var cif = valor.split('-')[1];
        if(valor==0) {
            // desactivamos el segundo select 
            document.getElementById("selectEmpleado").hidden=true; 
            document.getElementById("labelEmpleado").hidden=true;
        }else{
            // eliminamos todos los posibles valores que contenga el selectEmpleado 
            // añadimos los nuevos valores al selectEmpleado 
            var cont = 0;
            var ultima_empresa = [];
            document.getElementById("selectEmpleado").options[cont]=new Option("", "");
            var users = <?php echo json_encode($all_users); ?>;
            for(var i = 0; i < users.length; i++){
              if((users[i])["CIF"]==cif && (users[i])["EMP"]==empresa){
                cont = cont + 1;
                document.getElementById("selectEmpleado").options[cont]=new Option((users[i])["COD"].concat(' ', (users[i])["NOM"]), (users[i])["id"]);
                ultima_empresa.push((users[i])["COD"]);
              }
            }
            
            // habilitamos el segundo select 
            document.getElementById("selectEmpleado").hidden=false;
            document.getElementById("labelEmpleado").hidden=false;
      } 
    } 
  
  </script>

<div class="center">
    <div class="tarjeta">
        <div class="titulo" style="background-color:#F7AB40">
            <strong>Administrar Centro: {{$centro->NOM}}</strong>
        </div>

        <div class="center">
            <a href="#" data-toggle="modal" data-target="#cambioCentro" class="enlace">
                <div class="pop">
                    Buscar centro
                </div>
            </a>

            <!--Modal cambio centro-->
            <div>
                <div class="modal fade" id="cambioCentro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Buscar centro</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <?php
                                $conn=mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'),env('DB_DATABASE'));
                                $sql = "SELECT distinct(CIF), NOM_EMP FROM centros";
                                $result = mysqli_query($conn, $sql);
                            ?>

                            <div class="modal-body">
                            <form method="GET" action="{{ route('buscarCen') }}" enctype="multipart/form-data">
                                @csrf
                                <label for="selectCif" class="col-md-4 col-form-label text-md-right">{{ __('CIF Empresa:') }}</label>
                                <select id='selectCif' onchange='cargarSelectEmpresa(this.value);' required> 
                                    <option selected value="0"></option> 
                                    <?php while($row = mysqli_fetch_assoc($result)) {?>
                                        @if($row['CIF']!=null)
                                        <option value="{{$row['CIF']}}">{{$row['CIF'].' '.$row['NOM_EMP']}}</option>
                                        @endif
                                    <?php }?>
                                </select> 
                                        <br>
                                <label id="labelEmpresa" for="selectEmpresa" class="col-md-4 col-form-label text-md-right" hidden>{{ __('Empresa:') }}</label>
                                <select id='selectEmpresa'  onchange='cargarSelectEmpleado(this.value);' hidden required> </select> 
                                        <br>
                                <label id="labelEmpleado" for="selectEmpleado" class="col-md-4 col-form-label text-md-right" hidden>{{ __('COD Centro:') }}</label>
                                <select id='selectEmpleado' name='selectEmpleado' hidden required> </select>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Buscar') }}
                                        </button>
                                    </div>
                                </div>
                            </form>      
                            </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
            <!--Modal cambio centro-->
        </div>

        @if($movil)

        <div class="cuerpo">
            <div>
                <strong>Codigo:&nbsp;</strong> {{$centro->COD}} &nbsp;&nbsp;
                <strong>Nombre:&nbsp;</strong> {{$centro->NOM}} &nbsp;&nbsp;
            </div>
                <strong>CIF:&nbsp;</strong> {{$centro->CIF  }} &nbsp;&nbsp;
                <strong>Empresa:&nbsp;</strong> {{$centro->EMP}} &nbsp;&nbsp;
            <div>
            </div>

            <div class="center">
                <a href="#" data-toggle="modal" data-target="#cambioCortesia" class="enlace">
                    <div class="pop">
                        Cambiar minutos de cortesia
                    </div>
                </a>
            </div>

            <div class="center">
                <a href="#" data-toggle="modal" data-target="#cambioGeo" class="enlace">
                    <div class="pop">
                        Cambiar geolocalización.
                    </div>
                </a>
            </div>

            <div class="center">
                <strong><h2>Horario</h2></strong>
            </div>


            <div style="overflow:auto">
            <table>
                <thead>

                    <tr class="principal">

                        <th style="text-align:right"></th>

                        <th colspan="2" style="text-align:center">Mañana</th>

                        <th colspan="2" style="text-align:center">Tarde</th>

                        <th colspan="2" style="text-align:center">Noche</th>

                    </tr>

                    <tr class="principal">
                        <th style="text-align:right">Dia</th>

                        <th style="text-align:center">Desde</th>

                        <th style="text-align:center">Hasta</th>

                        <th style="text-align:center">Desde</th>

                        <th style="text-align:center">Hasta</th>

                        <th style="text-align:center">Desde</th>

                        <th style="text-align:center">Hasta</th>

                    </tr>

                </thead>

                <tbody>

                    <tr class="linea">

                        <th style="text-align:right">Lunes</th>

                        <td style="text-align:center">{{$turno->LMD}}</td>

                        <td style="text-align:center">{{$turno->LMH}}</td>

                        <td style="text-align:center">{{$turno->LTD}}</td>

                        <td style="text-align:center">{{$turno->LTH}}</td>

                        <td style="text-align:center">{{$turno->LND}}</td>

                        <td style="text-align:center">{{$turno->LNH}}</td>

                    </tr>

                    <tr>

                        <th style="text-align:right">Martes</th>

                        <td style="text-align:center">{{$turno->MMD}}</td>

                        <td style="text-align:center">{{$turno->MMH}}</td>

                        <td style="text-align:center">{{$turno->MTD}}</td>

                        <td style="text-align:center">{{$turno->MTH}}</td>

                        <td style="text-align:center">{{$turno->MND}}</td>

                        <td style="text-align:center">{{$turno->MNH}}</td>

                    </tr>

                    <tr class="linea">

                        <th style="text-align:right">Miercoles</th>

                        <td style="text-align:center">{{$turno->XMD}}</td>

                        <td style="text-align:center">{{$turno->XMH}}</td>

                        <td style="text-align:center">{{$turno->XTD}}</td>

                        <td style="text-align:center">{{$turno->XTH}}</td>

                        <td style="text-align:center">{{$turno->XND}}</td>

                        <td style="text-align:center">{{$turno->XNH}}</td>

                    </tr>

                    <tr>

                        <th style="text-align:right">Jueves</th>

                        <td style="text-align:center">{{$turno->JMD}}</td>

                        <td style="text-align:center">{{$turno->JMH}}</td>

                        <td style="text-align:center">{{$turno->JTD}}</td>

                        <td style="text-align:center">{{$turno->JTH}}</td>

                        <td style="text-align:center">{{$turno->JND}}</td>

                        <td style="text-align:center">{{$turno->JNH}}</td>

                    </tr>

                    <tr class="linea">

                        <th style="text-align:right">Viernes</th>

                        <td style="text-align:center">{{$turno->VMD}}</td>

                        <td style="text-align:center">{{$turno->VMH}}</td>

                        <td style="text-align:center">{{$turno->VTD}}</td>

                        <td style="text-align:center">{{$turno->VTH}}</td>

                        <td style="text-align:center">{{$turno->VND}}</td>

                        <td style="text-align:center">{{$turno->VNH}}</td>

                    </tr>

                    <tr>

                        <th style="text-align:right">Sabado</th>

                        <td style="text-align:center">{{$turno->SMD}}</td>

                        <td style="text-align:center">{{$turno->SMH}}</td>

                        <td style="text-align:center">{{$turno->STD}}</td>

                        <td style="text-align:center">{{$turno->STH}}</td>

                        <td style="text-align:center">{{$turno->SND}}</td>

                        <td style="text-align:center">{{$turno->SNH}}</td>

                    </tr>

                    <tr class="linea">

                        <th style="text-align:right">Domingo</th>

                        <td style="text-align:center">{{$turno->DMD}}</td>

                        <td style="text-align:center">{{$turno->DMH}}</td>

                        <td style="text-align:center">{{$turno->DTD}}</td>

                        <td style="text-align:center">{{$turno->DTH}}</td>

                        <td style="text-align:center">{{$turno->DND}}</td>

                        <td style="text-align:center">{{$turno->DNH}}</td>

                    </tr>
                    </tbody>
            </table>
            </div>
            <div class="center">
                <div class="pop">
                    <a href="{{route('inicioCen')}}" class="enlace">Inicio</a>
                </div>
                <a href="{{route('anteriorCen', ['centro' => $centro])}}" class="enlace">
                    <div class="pop"> 
                        Anterior
                    </div>
                </a>

                <a href="{{route('siguienteCen', ['centro' => $centro])}}" class="enlace">
                    <div class="pop">
                        Siguiente
                    </div>
                </a>    
                <div class="pop">
                    <a href="{{route('finCen')}}" class="enlace">Fin</a>  
                </div>
            </div>

            <div class="center">
                <div class="pop_alt">
                    <a href="{{ route('users.index')}}" class="enlace_alt">Regresar al Panel de Control</a>
                </div>
            </div>
        </div>
        @else
        <div class="cuerpo">
            <div class="center">
                <a href="#" data-toggle="modal" data-target="#cambioCortesia" class="enlace">
                    <div class="pop">
                        Cambiar minutos de cortesia
                    </div>
                </a>
            </div>
            <div class="center">
                <a href="#" data-toggle="modal" data-target="#cambioGeo" class="enlace">
                    <div class="pop">
                        Cambiar geolocalización.
                    </div>
                </a>
            </div>

            <div class="center">
                <div style="padding:20px">
                <pre>
<h5><strong>Codigo: </strong>{{$centro->COD}}<h5>
<h5><strong>Nombre: </strong>{{$centro->NOM}}<h5>
<h5><strong>CIF: </strong>{{$centro->CIF}}<h5>
<h5><strong>Empresa: </strong>{{$centro->EMP}}<h5>

                </pre>
                
                </div>

                <div style="padding:20px">
                    <strong>Horario: </strong>
                </div>
                <div style="padding:20px">
                <table class="miTabla">
                    <thead>

                        <tr class="principal">

                            <th style="text-align:right"></th>

                            <th colspan="2" style="text-align:center">Mañana</th>

                            <th colspan="2" style="text-align:center">Tarde</th>

                            <th colspan="2" style="text-align:center">Noche</th>

                        </tr>

                        <tr class="principal">
                            <th style="text-align:right">Dia</th>

                            <th style="text-align:center">Desde</th>

                            <th style="text-align:center">Hasta</th>

                            <th style="text-align:center">Desde</th>

                            <th style="text-align:center">Hasta</th>

                            <th style="text-align:center">Desde</th>

                            <th style="text-align:center">Hasta</th>

                        </tr>

                    </thead>

                    <tbody>

                    <tr class="linea">

                        <th style="text-align:right">Lunes</th>

                        <td style="text-align:center">{{$turno->LMD}}</td>

                        <td style="text-align:center">{{$turno->LMH}}</td>

                        <td style="text-align:center">{{$turno->LTD}}</td>

                        <td style="text-align:center">{{$turno->LTH}}</td>

                        <td style="text-align:center">{{$turno->LND}}</td>

                        <td style="text-align:center">{{$turno->LNH}}</td>

                    </tr>

                    <tr>

                        <th style="text-align:right">Martes</th>

                        <td style="text-align:center">{{$turno->MMD}}</td>

                        <td style="text-align:center">{{$turno->MMH}}</td>

                        <td style="text-align:center">{{$turno->MTD}}</td>

                        <td style="text-align:center">{{$turno->MTH}}</td>

                        <td style="text-align:center">{{$turno->MND}}</td>

                        <td style="text-align:center">{{$turno->MNH}}</td>

                    </tr>

                    <tr class="linea">

                        <th style="text-align:right">Miercoles</th>

                        <td style="text-align:center">{{$turno->XMD}}</td>

                        <td style="text-align:center">{{$turno->XMH}}</td>

                        <td style="text-align:center">{{$turno->XTD}}</td>

                        <td style="text-align:center">{{$turno->XTH}}</td>

                        <td style="text-align:center">{{$turno->XND}}</td>

                        <td style="text-align:center">{{$turno->XNH}}</td>

                    </tr>

                    <tr>

                        <th style="text-align:right">Jueves</th>

                        <td style="text-align:center">{{$turno->JMD}}</td>

                        <td style="text-align:center">{{$turno->JMH}}</td>

                        <td style="text-align:center">{{$turno->JTD}}</td>

                        <td style="text-align:center">{{$turno->JTH}}</td>

                        <td style="text-align:center">{{$turno->JND}}</td>

                        <td style="text-align:center">{{$turno->JNH}}</td>

                    </tr>

                    <tr class="linea">

                        <th style="text-align:right">Viernes</th>

                        <td style="text-align:center">{{$turno->VMD}}</td>

                        <td style="text-align:center">{{$turno->VMH}}</td>

                        <td style="text-align:center">{{$turno->VTD}}</td>

                        <td style="text-align:center">{{$turno->VTH}}</td>

                        <td style="text-align:center">{{$turno->VND}}</td>

                        <td style="text-align:center">{{$turno->VNH}}</td>

                    </tr>

                    <tr>

                        <th style="text-align:right">Sabado</th>

                        <td style="text-align:center">{{$turno->SMD}}</td>

                        <td style="text-align:center">{{$turno->SMH}}</td>

                        <td style="text-align:center">{{$turno->STD}}</td>

                        <td style="text-align:center">{{$turno->STH}}</td>

                        <td style="text-align:center">{{$turno->SND}}</td>

                        <td style="text-align:center">{{$turno->SNH}}</td>

                    </tr>

                    <tr class="linea">

                        <th style="text-align:right">Domingo</th>

                        <td style="text-align:center">{{$turno->DMD}}</td>

                        <td style="text-align:center">{{$turno->DMH}}</td>

                        <td style="text-align:center">{{$turno->DTD}}</td>

                        <td style="text-align:center">{{$turno->DTH}}</td>

                        <td style="text-align:center">{{$turno->DND}}</td>

                        <td style="text-align:center">{{$turno->DNH}}</td>

                    </tr>
                    </tbody>

                </table>

                
            </div>
            
        </div>
        <div class="center">
                <a href="{{route('inicioCen')}}" class="enlace">
                    <div class="pop">
                        Inicio
                    </div>
                </a>

                <a href="{{route('anteriorCen', ['centro' => $centro])}}" class="enlace">
                    <div class="pop"> 
                        Anterior
                    </div>
                </a>

                <a href="{{route('siguienteCen', ['centro' => $centro])}}" class="enlace">
                    <div class="pop">
                        Siguiente
                    </div>
                </a>

                <a href="{{route('finCen')}}" class="enlace">
                    <div class="pop">
                        Fin  
                    </div>
                </a>
            </div>

            <div class="center">
                <a href="{{ route('users.index')}}" class="enlace_alt">
                    <div class="pop_alt">
                        Regresar al Panel de Control
                    </div>
                </a>
            </div>
        @endif
    </div>
</div>


<!--Modal cambiar ubicacion-->
<div>
    <div class="modal fade" id="cambioGeo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cambiar geolocalización</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form method="POST" action="{{ route('cambiarGeo', ['id_centro' => $centro->id]) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        

                        <label for="geo" class="col-md-4 col-form-label text-md-right">{{ __('Posición:') }}</label>

                        <div class="col-md-6">
                        <input id="geo" type="text" name="geo" value="{{ old('geo') }}" required autofocus>

                            @if ($errors->has('geo'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('geo') }}</strong>
                                </span>
                            @endif
                        </div>

                        <label for="rango" class="col-md-4 col-form-label text-md-right">{{ __('Rango:') }}</label>

                        <div class="col-md-6">
                        <input id="rango" type="text" name="rango" value="{{ old('rango') }}" required autofocus>

                            @if ($errors->has('rango'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('rango') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="col-md-6">
                        <input maxlength="5" id="id" type="text" name="id" value="{{ $centro->id }}" autofocus style="display: none">

                            @if ($errors->has('id'))s
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('id') }}</strong>
                                </span>
                            @endif

                            
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Cambiar') }}
                            </button>
                        </div>
                    </div>
                </form>      
                </div>
            </div>
        </div>
    </div>
</div>


<!--Modal cambio de cortesia-->
<div>
    <div class="modal fade" id="cambioCortesia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cambiar cortesia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form method="POST" action="{{ route('cambiarCortesia', ['COD_centro' => $centro->COD]) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="minutos" class="col-md-4 col-form-label text-md-right">{{ __('Minutos:') }}</label>

                        <div class="col-md-6">
                        <input maxlength="5" id="minutos" type="text" name="minutos" value="{{ old('minutos') }}" required autofocus>

                            @if ($errors->has('minutos'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('minutos') }}</strong>
                                </span>
                            @endif

                            
                        </div>

                        <div class="col-md-6">
                        <input maxlength="5" id="id" type="text" name="id" value="{{ $centro->id }}" autofocus style="display: none">

                            @if ($errors->has('id'))s
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('id') }}</strong>
                                </span>
                            @endif

                            
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Cambiar') }}
                            </button>
                        </div>
                    </div>
                </form>      
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection