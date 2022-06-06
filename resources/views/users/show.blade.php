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
</style>

<?php
    use App\Empresa;
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
    $cen1 = $user -> CEN;
    $cen2 = $user -> CEN_02;
    $cen3 = $user -> CEN_03;
    $cen4 = $user -> CEN_04;
    $cen5 = $user -> CEN_05;

    $con=mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'),env('DB_DATABASE'));

    $centros = array($cen1, $cen2, $cen3, $cen4, $cen5);
    $noms = array(null, null, null, null, null);

    $cont = 0;
    foreach($centros as $cen){

        if($cen!=null){
        $sql_centro = "SELECT NOM from centros where COD = $cen";
        $result_centro = mysqli_query($con, $sql_centro);
        $noms[$cont] = ($result_centro->fetch_array(MYSQLI_NUM))[0];
        }

        $cont++;
    }
?>


<div class="center">    
    <div class="tarjeta">
        <div class="titulo" style="background-color:#F7AB40">
            <strong>Perfil de {{$user->NOM}}</strong>
        </div>
            <div class="center">
                @if(Auth::user()->role=='supervisor')
                <a class="enlace" href="#" data-toggle="modal" data-target="#cambioUser">
                    <div class="pop">
                        Buscar usuario
                    </div>
                </a>
                @endif    
            </div>
        @if($movil)
        <div class="cuerpo">
            <div>
                <strong>Codigo:&nbsp;</strong> {{$user->COD}} &nbsp;&nbsp;
                <strong>Empresa:&nbsp;</strong> {{$user->EMP}} &nbsp;&nbsp;
                @if($user->role=='supervisor')
                    <strong>Rol:&nbsp;</strong> Supervisor &nbsp;&nbsp;
                @else
                    <strong>Rol:&nbsp;</strong> Empleado &nbsp;&nbsp;
                @endif
            </div>
            </br>              
            <div>
                <strong>Apellidos: </strong> {{$user->AP1}},  {{$user->AP2}} &nbsp;&nbsp;
                <strong>Nombre: </strong> {{$user->NOM}} &nbsp;&nbsp;
            </div>
            </br>   
            <div>
                <strong>DNI: </strong> {{$user->DNI}} &nbsp;&nbsp;
                <strong>Fecha de alta: </strong> {{$user->FAL}} &nbsp;&nbsp;
            </div>
            </br>   
            <div>
                <strong>Email: </strong> {{$user->email}} &nbsp;&nbsp;
            </div>
            </br>
            <div>
                <strong>Teléfono: </strong> ... &nbsp;&nbsp;
                <strong>Ubicación: </strong> ... &nbsp;&nbsp;
            </div>   

            <div class="center">
                <strong style="padding: 5px;">Horario centros</strong>
            </div>

            <div class="center">

                <div class="pop">
                    <a class="enlace" href="{{route('cambioHorario', ['id' => $user->id, 'centro'=>1])}}">{{$cen1}}</a> 
                </div>

                @if($user->CEN_02!=NULL)
                <div class="pop">
                    <a class="enlace" href="{{route('cambioHorario', ['id' => $user->id, 'centro'=>2])}}">{{$cen2}}</a> 
                </div>
                @endif

                @if($user->CEN_03!=NULL)
                <div class="pop">
                    <a class="enlace" href="{{route('cambioHorario', ['id' => $user->id, 'centro'=>3])}}">{{$cen3}}</a> 
                </div>
                @endif

                @if($user->CEN_04!=NULL)
                <div class="pop">
                    <a class="enlace" href="{{route('cambioHorario', ['id' => $user->id, 'centro'=>4])}}">{{$cen4}}</a> 
                </div>
                @endif

                @if($user->CEN_05!=NULL)
                <div class="pop">
                    <a class="enlace" href="{{route('cambioHorario', ['id' => $user->id, 'centro'=>5])}}">{{{$cen5}}}</a> 
                </div>
                @endif
            </div>
            <center><strong>Horario de centro: {{$centro->NOM}}</strong></center>
            <br>
                <div style="overflow:auto">
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
            </br>
            @if(Auth::user()->role=='supervisor'||Auth::user()->role=='jefe')
            <div class="center">
                <a href="{{route('inicio')}}" class="enlace_alt">
                    <div class="pop_alt">    
                        Inicio
                    </div>
                </a> 

                <a href="{{route('anterior', ['usuario' => $user])}}" class="enlace_alt">
                    <div class="pop_alt">
                        Anterior                        
                    </div>
                </a> 
                
                <a href="{{route('siguiente', ['usuario' => $user])}}" class="enlace_alt">
                    <div class="pop_alt">
                        Siguiente  
                    </div>
                </a>

                <a href="{{route('fin')}}" class="enlace_alt">
                    <div class="pop_alt">
                        Fin
                    </div>
                </a>
            </div>  
            @endif
            
            <div class="center">
                @if(Auth::user()->role=="supervisor")
                <a href="" data-toggle="modal" data-target="#editModal{{ $user->id }}" class="enlace">
                    <div class="pop">
                        Editar
                    </div>
                </a>
                @endif
                <a href="{{ route('users.index')}}" class="enlace_alt">
                    <div class="pop_alt">
                        Regresar al Panel de Control
                    </div>
                </a>
            </div>
        </div> 
        @else
        <div class="cuerpo">

            <div class="center">
                <div style="padding:20px">
                <p style="font-family:Arial; font-size:19px">
<strong>Codigo: </strong>{{$user->COD}} <br>
<strong>Apellidos: </strong>{{$user->AP1}}, {{$user->AP2}}<br>
<strong>Nombre: </strong>{{$user->NOM}}<br>
<strong>Empresa: </strong>{{$user->EMP  }}<br>
<strong>Rol: </strong>{{$user->role}}<br>
<strong>DNI: </strong>{{$user->DNI}}<br>
<strong>Fecha de alta: </strong>{{$user->FAL}}<br>
<strong>Email: </strong>{{$user->email}}<br>
                </p>
                
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
            <div class="pop">
                    <a class="enlace" href="{{route('cambioHorario', ['id' => $user->id, 'centro'=>1])}}">{{$cen1}}</a> 
                </div>

                @if($user->CEN_02!=NULL)
                <div class="pop">
                    <a class="enlace" href="{{route('cambioHorario', ['id' => $user->id, 'centro'=>2])}}">{{$cen2}}</a> 
                </div>
                @endif

                @if($user->CEN_03!=NULL)
                <div class="pop">
                    <a class="enlace" href="{{route('cambioHorario', ['id' => $user->id, 'centro'=>3])}}">{{$cen3}}</a> 
                </div>
                @endif

                @if($user->CEN_04!=NULL)
                <div class="pop">
                    <a class="enlace" href="{{route('cambioHorario', ['id' => $user->id, 'centro'=>4])}}">{{$cen4}}</a> 
                </div>
                @endif

                @if($user->CEN_05!=NULL)
                <div class="pop">
                    <a class="enlace" href="{{route('cambioHorario', ['id' => $user->id, 'centro'=>5])}}">{{{$cen5}}}</a> 
                </div>
                @endif
            </div>
            <div class="center">
            @if(Auth::user()->role=='supervisor'||Auth::user()->role=='jefe')
            <div class="center">
                <a href="{{route('inicio')}}" class="enlace">
                    <div class="pop">    
                        Inicio
                    </div>
                </a> 

                <a href="{{route('anterior', ['usuario' => $user])}}" class="enlace">
                    <div class="pop">
                        Anterior                        
                    </div>
                </a> 
                
                <a href="{{route('siguiente', ['usuario' => $user])}}" class="enlace">
                    <div class="pop">
                        Siguiente  
                    </div>
                </a>

                <a href="{{route('fin')}}" class="enlace">
                    <div class="pop">
                        Fin
                    </div>
                </a>
            </div> 
            @endif
            </div>

            <div class="center">
                @if(Auth::user()->role=='supervisor')
                <a href="" data-toggle="modal" data-target="#editModal{{ $user->id }}" class="enlace">
                    <div class="pop">
                        Editar
                    </div>
                </a>
                @endif
                <a href="{{ route('users.index')}}" class="enlace_alt">
                    <div class="pop_alt">
                        Regresar al Panel de Control
                    </div>
                </a>
            </div>
        @endif   
    </div>
</div>

<div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modificar Perfil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                      <form method="POST" action="{{ route('users.update', $user->id) }}" id="edit{{ $user->id }}" enctype="multipart/form-data" onSubmit="arrayImg({{ $user->id }})">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group row">
                            <label for="cod" class="col-md-4 col-form-label text-md-right">{{ __('Codigo:') }}</label>

                            <div class="col-md-6">
                            <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="5" id="cod" type="text" value="{{ $user->COD }}" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="cod" value="{{ old('cod') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cod') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre:') }}</label>

                            <div class="col-md-6">
                            <input maxlength="20" id="name" type="text" value="{{ $user->NOM }}" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="ap1" class="col-md-4 col-form-label text-md-right">{{ __('Primer apellido:') }}</label>

                            <div class="col-md-6">
                            <input maxlength="20" id="ap1" type="text" value="{{ $user->AP1 }}" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="ap1" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cod') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="ap2" class="col-md-4 col-form-label text-md-right">{{ __('Segundo apellido:') }}</label>

                            <div class="col-md-6">
                            <input maxlength="20" id="ap2" type="text" value="{{ $user->AP2 }}" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="ap2" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cod') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cen1" class="col-md-4 col-form-label text-md-right">{{ __('Centro 1:') }}</label>

                            <div class="col-md-6">
                                <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="3" id="cen1" type="text" value="{{ $user->CEN }}" class="form-control{{ $errors->has('cen1') ? ' is-invalid' : '' }}" name="cen1" value="{{ old('email') }}">

                                    @if ($errors->has('cen1'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('cen1') }}</strong>
                                        </span>
                                    @endif
                            </div>

                        </div> 

                        <div class="form-group row">
                            <label for="cen2" class="col-md-4 col-form-label text-md-right">{{ __('Centro 2:') }}</label>

                            <div class="col-md-6">
                                <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="3" id="cen2" type="text" value="{{ $user->CEN_02 }}" class="form-control{{ $errors->has('cen2') ? ' is-invalid' : '' }}" name="cen2" value="{{ old('email') }}">

                                    @if ($errors->has('cen2'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('cen2') }}</strong>
                                        </span>
                                    @endif
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="cen3" class="col-md-4 col-form-label text-md-right">{{ __('Centro 3:') }}</label>

                            <div class="col-md-6">
                                <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="3" id="cen3" type="text" value="{{ $user->CEN_03 }}" class="form-control{{ $errors->has('cen3') ? ' is-invalid' : '' }}" name="cen3" value="{{ old('email') }}">

                                    @if ($errors->has('cen3'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('cen3') }}</strong>
                                        </span>
                                    @endif
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="cen4" class="col-md-4 col-form-label text-md-right">{{ __('Centro 4:') }}</label>

                            <div class="col-md-6">
                                <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="3" id="cen4" type="text" value="{{ $user->CEN_04 }}" class="form-control{{ $errors->has('cen4') ? ' is-invalid' : '' }}" name="cen4" value="{{ old('email') }}">

                                    @if ($errors->has('cen4'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('cen4') }}</strong>
                                        </span>
                                    @endif
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="cen5" class="col-md-4 col-form-label text-md-right">{{ __('Centro 5:') }}</label>

                            <div class="col-md-6">
                                <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="3" id="cen5" type="text" value="{{ $user->CEN_05 }}" class="form-control{{ $errors->has('cen5') ? ' is-invalid' : '' }}" name="cen5" value="{{ old('email') }}">

                                    @if ($errors->has('cen5'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('cen5') }}</strong>
                                        </span>
                                    @endif
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="dni" class="col-md-4 col-form-label text-md-right">{{ __('DNI:') }}</label>

                            <div class="col-md-6">
                                <input maxlength="9" id="dni" type="text" value="{{ $user->DNI }}" class="form-control{{ $errors->has('dni') ? ' is-invalid' : '' }}" name="dni" value="{{ old('email') }}">

                                    @if ($errors->has('cen'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('cen') }}</strong>
                                        </span>
                                    @endif
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="fal" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de alta:') }}</label>

                            <div class="col-md-6">
                                <input id="fal" type="date" value="{{ $user->FAL }}" class="form-control{{ $errors->has('cen') ? ' is-invalid' : '' }}" name="fal" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="emp" class="col-md-4 col-form-label text-md-right">{{ __('Empresa:') }}</label>

                            <div class="col-md-6">
                                <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="4" id="emp" type="text" value="{{ $user->EMP }}" class="form-control{{ $errors->has('cen') ? ' is-invalid' : '' }}" name="emp" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Dirección de correo:') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" value="{{ $user->email }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                            </div>
                        </div>
                        @if(Auth::user()->role == 'supervisor')
                        <div class="form-group row">
                            <label for="absenteeism" class="col-md-4 col-form-label text-md-right">{{ __('Días de absentismo:') }}</label>

                            <div class="col-md-6">
                                <input id="absenteeism" type="number" value="{{ $user->absenteeism }}" class="form-control{{ $errors->has('absenteeism') ? ' is-invalid' : '' }}" name="absenteeism" value="{{ old('absenteeism') }}">

                                    @if ($errors->has('absenteeism'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('absenteeism') }}</strong>
                                        </span>
                                    @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="days_holidays" class="col-md-4 col-form-label text-md-right">{{ __('Días de vacaciones pendientes:') }}</label>

                            <div class="col-md-6">
                                <input id="days_holidays" type="text" value="{{ $user->days_holidays }}" class="form-control{{ $errors->has('days_holidays') ? ' is-invalid' : '' }}" name="days_holidays" value="{{ old('days_holidays') }}">

                                    @if ($errors->has('days_holidays'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('days_holidays') }}</strong>
                                        </span>
                                    @endif
                            </div>
                        </div> 
                        @endif
                      </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" form="edit{{ $user->id }}">Guardar Cambios</button>
                      </div>
                    </div>
                  </div>
              </div>





<!--
    Modales
-->

        <!--Modal buscar usuario-->
        <div class="modal fade" id="cambioUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Buscar Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
<?php
    use App\User;
    $all_users = User::all()->toArray();  
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
                document.getElementById("selectEmpleado").options[cont]=new Option((users[i])["COD"].concat(' ', (users[i])["AP1"], ' ', (users[i])["AP2"], ', ', (users[i])["NOM"]), (users[i])["id"]);
                ultima_empresa.push((users[i])["COD"]);
              }
            }
            
            // habilitamos el segundo select 
            document.getElementById("selectEmpleado").hidden=false;
            document.getElementById("labelEmpleado").hidden=false;
      } 
    } 
  
  </script>
                    
                      <div class="modal-body">
                      <?php
                        $id_sup = Auth::user()->id;
                        $empresas = Empresa::where('id_supervisor', '=', $id_sup)->get()->all();
                        $array_cifs = array();

                        foreach($empresas as $emp){
                          array_push($array_cifs, $emp->CIF);
                        }
                      ?>
                      <form method="GET" action="{{ route('buscarUse') }}" enctype="multipart/form-data">
                      <label for="selectCif" class="col-md-4 col-form-label text-md-right">{{ __('CIF Empresa:') }}</label>
                      <select id='selectCif' onchange='cargarSelectEmpresa(this.value);' required> 
                          <option selected value="0"></option> 
                          @foreach($array_cifs as $cif)
                              <option value="{{$cif}}">{{$cif}}</option>
                          @endforeach
                      </select> 
                            <br>
                        <label id="labelEmpresa" for="seleselectEmpresactCif" class="col-md-4 col-form-label text-md-right" hidden>{{ __('Empresa:') }}</label>

                      <select id='selectEmpresa'  onchange='cargarSelectEmpleado(this.value);' hidden required> </select> 
                            <br>
                            <label id="labelEmpleado" for="selectEmpleado" class="col-md-4 col-form-label text-md-right" hidden>{{ __('COD Empleado:') }}</label>
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
                </form>      
                </div>
            </div>
        </div>

    <!--Modal editar usuario-->
@endsection