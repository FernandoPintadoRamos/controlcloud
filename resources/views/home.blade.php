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

    .pop{
        padding:10px;
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
        color:black;
        text-decoration:none;
    }

    .enlace_alt{
        color:white;
    }

    .enlace_alt:hover{
        color:#E2870C;
        text-decoration:none;
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

    .clockdate-wrapper{
      text-align: center;
      font-size: 30px;
    }
</style>

<?php
  use App\Agenda;
  use App\Centro;

  $avisos = Agenda::where('usuario_receptor', '=', Auth::user()->id)->where('visto', '=', '0')->get()->toArray();
  $num_avisos = sizeof($avisos);
?>

<script>
  window.onload = miUbicacion;

function miUbicacion(){
		//Si los servicios de geolocalización están disponibles
		if(navigator.geolocation){
		// Para obtener la ubicación actual llama getCurrentPosition.
		navigator.geolocation.getCurrentPosition( muestraMiUbicacion );
		}else{ //de lo contrario
		alert("Los servicios de geolocalizaci\363n  no est\341n disponibles");
		}
}
function muestraMiUbicacion(posicion){
		var latitud = posicion.coords.latitude
		var longitud = posicion.coords.longitude
		var output = document.getElementById("ubicacion").value = latitud + ',' + longitud;
    startTime();
}

function startTime() {
    var today = new Date();
    var hr = today.getHours();
    var min = today.getMinutes();
    var sec = today.getSeconds();
    //Add a zero in front of numbers<10
    min = checkTime(min);
    sec = checkTime(sec);
    document.getElementById("clock").innerHTML = hr + " : " + min + " : " + sec;
    var time = setTimeout(function(){ startTime() }, 500);
}
function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}
</script>

<?php

  

  $cen1 = Auth::user() -> CEN;
  $cen2 = Auth::user() -> CEN_02;
  $cen3 = Auth::user() -> CEN_03;
  $cen4 = Auth::user() -> CEN_04;
  $cen5 = Auth::user() -> CEN_05;

  $_cen1 = Centro::where('COD', '=', $cen1)
                  ->where('EMP', '=', Auth::user() -> EMP)
                  ->where('CIF', '=', Auth::user() -> CIF)
                  ->first();
  $_cen2 = Centro::where('COD', '=', $cen2)
                  ->where('EMP', '=', Auth::user() -> EMP)
                  ->where('CIF', '=', Auth::user() -> CIF)
                  ->first();
  $_cen3 = Centro::where('COD', '=', $cen3)
                  ->where('EMP', '=', Auth::user() -> EMP)
                  ->where('CIF', '=', Auth::user() -> CIF)
                  ->first();
  $_cen4 = Centro::where('COD', '=', $cen4)
                  ->where('EMP', '=', Auth::user() -> EMP)
                  ->where('CIF', '=', Auth::user() -> CIF)
                  ->first();
  $_cen5 = Centro::where('COD', '=', $cen5)
                  ->where('EMP', '=', Auth::user() -> EMP)
                  ->where('CIF', '=', Auth::user() -> CIF)
                  ->first();

  
  $con=mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'),env('DB_DATABASE'));

  $centros = array($cen1, $cen2, $cen3, $cen4, $cen5);
  $noms = array(null, null, null, null, null);

  $cont = 0;
  
  
  foreach($centros as $centro){

    if($centro!=null){
      $sql_centro = "SELECT NOM from centros where COD = $centro";
      $result_centro = mysqli_query($con, $sql_centro);
      if($result_centro==null){
        $noms[$cont] = ($result_centro->fetch_array(MYSQLI_NUM))[0];
      }
    }
    $cont++;
  }
  
?>
<body onload="miUbicacion()">
<div class="center" >
    <div class="tarjeta">
        <div class="titulo" style="background-color:#F7AB40">
            <strong>Perfil de Usuario: {{Auth::user()->NOM}}</strong>
        </div>
        <div class="cuerpo">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if(Auth::user()->isAdmin())
                <strong>Hola usuario supervisor.</strong>
                </br>
            @endif

            @if(Auth::user()->role=='supervisor')
            <a href="{{route('avisos')}}" class="enlace_alt">
            <div class="pop_alt">
              Avisos
              @if($num_avisos!=0)
                <big><sup class="not">{{$num_avisos}}</sup></big>
              @endif
            </div>
            </a>
            @endif
            </br>
            <a class="enlace" href="{{ route('users.index') }}">
            <div class="pop">
              <span class="glyphicon glyphicon-user"></span> Acceder al Portal 
            </div>   
            </a>
            </br>
            
      
      @if($result_centro->fetch_array(MYSQLI_NUM)!=null)
      <form method="GET" action="{{route('marcajes.create',['centro'])}}" id="selector" enctype="multipart/form-data">
      @csrf
      <input id="ubicacion" name="ubicacion" hidden></input>  

      <div class="center">
        <strong>Centro: </strong> 
      </div>
        <select class="form-control{{ $errors->has('centro') ? ' is-invalid' : '' }}" id="centros" name="centros" required>          @if ($cen1 != null&&$_cen1!=null)
            <option value="1">{{$cen1.' '.$_cen1->NOM}}</option>
          @endif  

          @if ($cen2 != null)
            <option  value="2">{{$cen2.' '.$_cen2->NOM}}</option>
          @endif

          @if ($cen3 != null)
            <option  value="3">{{$cen3.' '.$_cen3->NOM}}</option>
          @endif

          @if ($cen4 != null)
            <option  value="4">{{$cen4.' '.$_cen4->NOM}}</option>
          @endif

          @if ($cen5 != null)
            <option  value="5">{{$cen5.' '.$_cen5->NOM}}</option>
          @endif
        </select>
        
                    
    </form>
    <div id="clockdate">
      <div class="clockdate-wrapper">
        <div id="clock"></div>
        <div id="date"></div>
    </div>
</div>
    <div class="center">
      <button type="submit" form="selector" style="background:#F9A958;border: 1px solid #E2870C;padding:10px;margin:10px;border-radius:20px;width:100px;height:100px">Picar</button>
    </div>
    <a class="enlace" 
            href="{{ route('logout') }}"
            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
    <div class="pop" style="width:40px">
        
            <span class="glyphicon glyphicon-off"></span>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
            </form>
        
    </div>
    </a>
    </div>   
    
    @endif 
    
</div>
@endsection
