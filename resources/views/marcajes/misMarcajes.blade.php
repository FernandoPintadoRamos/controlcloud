@extends('layouts.app')

@section('content')
<?php
  use App\User;
  use App\Centro;
  use App\Marcajes;
  use App\Empresa;
?>
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

<?php
  $user = Auth::user();
  $id = $user->id;

  $con=mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'),env('DB_DATABASE'));
  $sql = "SELECT * from users WHERE id = $id";
  $result = mysqli_query($con, $sql);
  $crow = mysqli_fetch_assoc($result);
?>

  <style>
    .uper {
      margin-top: 50px;
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
        color:black;
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

    td{
      border-top: 1px solid #E2870C;
      border-bottom: 1px solid #E2870C;
    }

    .principal{
        background-color:#F7AD62;
    }

    .linea{
        background-color:#FAC896;
    }

    .scrollH {
        width: auto;
        overflow-x: auto;
        white-space: nowrap;
    }

    .clockdate-wrapper{
      text-align: center;
      font-size: 30px;
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

<script>

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
    ComboAño();
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


<body onload="miUbicacion()">
  <!-- Mensaje de alerta -->
  <div class="container uper">
    @if(session()->get('success'))
      <div class="alert alert-success">
        {{ session()->get('success') }}  
      </div><br />
    @endif
  </div>
  
<div class="center">
  <div class="tarjeta">
    <div class="titulo" style="background-color:#F7AB40">
      <h5><strong>Marcajes y Gestion laboral</strong></h5>
    </div>
    @if($use->role == 'supervisor')
      <div class="center">
        <!--<a href="" data-toggle="modal" data-target="#pdfModal" class="enlace">
          <div class="pop">
            Generar PDF Registro Horario  
          </div>
        </a>-->      
        <a href="" data-toggle="modal" data-target="#csvModal" class="enlace">
          <div class="pop">
            Generar CSV marcajes
          </div> 
        </a>
        <!--Modal Generar PDF-->
        <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Elaborar Informe - Registro de jornada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              <form method="POST" action="{{ route('pdf') }}" id="genePdf">
                @csrf
                @method('POST')
                <div class="form-group row">
                  <label for="fecha_entrada" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de entrada:') }}</label>

                  <div class="col-md-6">
                      <input id="fecha_entrada" type="date" class="form-control{{ $errors->has('fecha_entrada') ? ' is-invalid' : '' }}" name="fecha_entrada">

                          @if ($errors->has('fecha_entrada'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('fecha_entrada') }}</strong>
                              </span>
                          @endif
                  </div>
                </div>

                <div class="form-group row">
                  <label for="fecha_salida" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de salida') }}</label>

                  <div class="col-md-6">
                      <input id="fecha_salida" type="date" class="form-control{{ $errors->has('fecha_salida') ? ' is-invalid' : '' }}" name="fecha_salida">

                          @if ($errors->has('fecha_salida'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('fecha_salida') }}</strong>
                              </span>
                          @endif
                  </div>
                </div>
                @if(isset($fecha_inicio) && isset($fecha_fin))
                  <input type="hidden" name="fecha_entrada" value="{{ $fecha_entrada }}">
                  <input type="hidden" name="fecha_salida" value="{{ $fecha_salida }}">
                @endif
              </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-outline-danger" form="genePdf">Generar PDF</button>
              </div>
            </div>
          </div>
        </div>
        <!--Fin Modal generar PDF-->


        <!--Modal generar CSV-->
        <div class="modal fade" id="csvModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Elaborar CSV - Registro de jornada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">

              

              <?php
                $conn = mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'),env('DB_DATABASE'));
                $sql = "SELECT distinct(CIF), NOM_EMP FROM users";
                $result = mysqli_query($conn, $sql);
              ?>
              <form method="POST" action="{{ route('csv') }}" id="geneCsv">
                @csrf
                @method('POST')
                <div class="center">
                  <div class="fila">
                    <strong>Mes:</strong>
                    <label for='desdeMes'>Desde</label>
                    <select name='desdeMes' id='desdeMes' onchange='comprobarValidezMes();'>
                      
                    <option selected></option>
                    <?php
                      $cont = 1;
                      while($cont <= 12){
                    ?>
                    <option value={{$cont}}>{{$cont}}</option>
                    <?php
                      $cont = $cont +1;
                      } 
                    ?>
                    </select>
                    
                    <label for='hastaMes'>Hasta</label>
                    <select name='hastaMes' id='hastaMes' onchange='comprobarValidezMes();'>
                    <option selected></option>
                    <?php
                      $cont = 1;
                      while($cont <= 12){
                    ?>
                    <option value={{$cont}}>{{$cont}}</option>
                    <?php
                      $cont = $cont +1;
                      } 
                    ?>
                    </select>
                  </div>
                </div>
                <div class="center">
                  <p name="erorMes" id="erorMes" style="color:red">
                  </p>
                </div>


                <div class="center">
                  <div class="fila">
                    <strong>Año:</strong>
                    <label for='desdeAño'>Desde</label>
                    <select name='desdeAño' id='desdeAño' onchange='comprobarValidezAño();' >
                      <option selected></option>
                    </select>
                    
                    <label for='hastaAño'>Hasta</label>
                    <select name='hastaAño' id='hastaAño' onchange='comprobarValidezAño();'>
                      <option selected></option>
                    </select>
                  </div>
                </div>
                <div class="center">
                  <p name="erorAño" id="erorAño" style="color:red">
                  </p>
                </div>
                <?php
                  $id_sup = Auth::user()->id;
                  $empresas = Empresa::where('id_supervisor', '=', $id_sup)->get()->all();
                  $array_cifs = array();

                  foreach($empresas as $emp){
                    array_push($array_cifs, $emp->CIF);
                  }
                ?>
                <div>
                  <div class="center">
                    <label for="selectCif"><strong>CIF: </strong></label>
                    <select id='selectCif' onchange='cargarSelectEmpresa(this.value);' required>
                        <option selected></option>
                          @foreach($array_cifs as $cif)
                            <option value="{{$cif}}">{{$cif}}</option>
                          @endforeach
                    </select>
                  </div>
                  <br>
                  <div class="center">
                    <label id="labelEmpresa" for="selectEmpresa" ><strong>Empresa: </strong></label>
                    <select style="min-width:200px" id='selectEmpresa' onchange="rellenarSelectCentros(this.value)" required></select>
                  </div>
                  <br>
                  <div class="center">
                    <div class="fila">
                      <strong>Centro: </strong>
                      <br>
                      <label id="labelCentroDesde" for="selectCentroDesde">Desde:</label>
                      <select style="min-width:200px" id='selectCentroDesde' name='selectCentroDesde' onchange="ajustarCentroHasta(this.value)"></select>
                      <br>
                      <label id="labelCentroHasta" for="selectCentroHasta" >Hasta:</label>
                      <select style="min-width:200px" id='selectCentroHasta' name='selectCentroHasta' onchange="comprobarCentroHastaMayor(this.value)"></select>

                      <p style="color:red" id="errorCentro"></p>
                    </div>
                  </div>
                  <br>
                  <div class="center">
                    <div class="fila">
                      <strong>Empleado: </strong>
                      <br>
                      <label id="labelEmpleadoDesde" for="selectEmpleadoDesde">Desde:</label>
                      <select style="min-width:250px" id='selectEmpleadoDesde' name='selectEmpleadoDesde' onchange="ajustarEmpleadoHasta(this.value)"></select>
                      <br>
                      <label id="labelEmpleadoHasta" for="selectEmpleadoHasta" >Hasta:</label>
                      <select style="min-width:250px" id='selectEmpleadoHasta' name="selectEmpleadoHasta" onchange="comprobarEmpleadoHastaMayor(this.value)"></select>

                      <p style="color:red" id="errorEmpleado"></p>
                    </div>
                  </div>

                  <div class="center">
                    <label id="labelTipoInforme" for="selectTipoInforme" ><strong>Tipo:</strong></label>
                    <select style="min-width:250px" id='selectTipoInforme' name="selectTipoInforme" required>
                          <option selected> </option>
                          <option value = "D"> D (Diario) </option>
                          <option value = "M"> M (Mensual)</option>
                    </select>
                  </div>
                </div>
              </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button id="butCsv" type="submit" class="btn btn-outline-danger" form="geneCsv">Generar CSV</button>
              </div>
            </div>
          </div>
        </div>
        <!--Fin Modal generar CSV-->
      </div>

        {{$turno}}
          
    @endif
    <?php
      $all_users = User::orderBy('AP1')->orderBy('AP2')->orderBy('NOM')->orderBy('COD')->get()->toArray();
      $all_centros = Centro::orderBy('NOM')->get()->toArray();
      
    ?>
    <script>

      function desactivarBoton(){
        if(
          (document.getElementById("desdeMes").value == 0 && document.getElementById("hastaMes").value != 0) ||
          document.getElementById("desdeAño").value > document.getElementById("hastaAño").value || (document.getElementById("desdeAño").value == 0 && document.getElementById("hastaAño").value != 0) ||
          parseInt(document.getElementById("selectEmpleadoDesde").value) > parseInt(document.getElementById("selectEmpleadoHasta").value) || parseInt(document.getElementById("selectEmpleadoHasta").value) == 0 ||
          parseInt(document.getElementById("selectCentroDesde").value) > parseInt(document.getElementById("selectCentroHasta").value) || parseInt(document.getElementById("selectCentroHasta").value) == null
          )
        {
          document.getElementById("butCsv").disabled = true;
        }else{
          document.getElementById("butCsv").disabled = false;
        }
      }

      function comprobarEmpleadoHastaMayor(valor){

        var empleado_desde = parseInt(document.getElementById("selectEmpleadoDesde").value);
        var empleado_hasta = parseInt(valor);

        if(empleado_desde > empleado_hasta){
          document.getElementById("errorEmpleado").textContent = "Hasta debe ser mayor que Desde";
          desactivarBoton();
        }else{
          document.getElementById("errorEmpleado").textContent = "";
          desactivarBoton();
        }
      }

      function ajustarEmpleadoHasta(valor){
        document.getElementById("selectEmpleadoHasta").value = valor;
        comprobarEmpleadoHastaMayor(document.getElementById("selectEmpleadoHasta").value);
      }

      function rellenarEmpleados(){
        var valor_desde = document.getElementById("selectCentroDesde").value;
        var valor_hasta = document.getElementById("selectCentroHasta").value;

        // Datos Empresa y CIF
        var empresa = parseInt(valor_desde.split('-')[1]);
        var cif = (valor_desde.split('-')[2]);

        //Recogemos los nombres de los centros
        var nom_desde = valor_desde.split('-')[0];
        var nom_hasta = valor_hasta.split('-')[0];

        //Array de todos los centros
        var centros = <?php echo json_encode($all_centros); ?>;
        var users = <?php echo json_encode($all_users); ?>;

        // Recogemos centros entre limites
        var array_centros = [];
        var entrar = 0;
        for(var i = 0; i < centros.length; i++){

          if(centros[i]['NOM'] == nom_desde){
            entrar = 1;
          }

          if(centros[i]['NOM'] == nom_hasta){
            array_centros.push(parseInt(centros[i]['COD']));
            entrar = 0;
          }

          if(entrar == 1){
            array_centros.push(parseInt(centros[i]['COD']));
          }
        }

        var cont = 0;

        document.getElementById("selectEmpleadoDesde").options.length=0; 
        document.getElementById("selectEmpleadoHasta").options.length=0; 

        document.getElementById("selectEmpleadoDesde").options[cont]=new Option("", "");
        document.getElementById("selectEmpleadoHasta").options[cont]=new Option("", "");
        for(var x = 0; x < users.length; x++){
          if(
              users[x]['CIF'] == cif &&
              users[x]['EMP'] == empresa
            )
          {
            // Centros del user
            var array_centros_user = [];

            if(users[x]['CEN'] != null) { array_centros_user.push(parseInt(users[x]['CEN'])); }
            if(users[x]['CEN_02'] != null) { array_centros_user.push(parseInt(users[x]['CEN_02'])); }
            if(users[x]['CEN_03'] != null) { array_centros_user.push(parseInt(users[x]['CEN_03'])); }
            if(users[x]['CEN_04'] != null) { array_centros_user.push(parseInt(users[x]['CEN_04'])); }
            if(users[x]['CEN_05'] != null) { array_centros_user.push(parseInt(users[x]['CEN_05'])); }
            
            var valido = 0;

            if(nom_desde == ""){
              valido = 1;
            }else{
              for(var y = 0; y < array_centros_user.length; y++){
              for(var w = 0; w < array_centros.length; w++){
                if(array_centros_user[y] == array_centros[w]){
                  valido = 1;
                }
              }
            }
            }

            if(valido == 1){
              if(users[x]["role"] != 'supervisor'){
                cont++;
                document.getElementById("selectEmpleadoDesde").options[cont]=new Option(users[x]["AP1"] + ' ' + users[x]["AP2"] + ', ' + users[x]["NOM"].concat(' (', users[x]["COD"]) + ')', users[x]["NOM"] + '-' + users[x]["AP1"] + '-' + users[x]["AP2"] + '-' + users[x]["COD"]);
                document.getElementById("selectEmpleadoHasta").options[cont]=new Option(users[x]["AP1"] + ' ' + users[x]["AP2"] + ', ' + users[x]["NOM"].concat(' (', users[x]["COD"]) + ')', users[x]["NOM"] + '-' + users[x]["AP1"] + '-' + users[x]["AP2"] + '-' + users[x]["COD"]);   
              }
            }
          }
        }
      }

      function comprobarCentroHastaMayor(valor){
        var centro_hasta = parseInt(valor.split('-')[0]);
        var centro_desde = parseInt(document.getElementById("selectCentroDesde").value.split('-')[0]);
        if(centro_hasta < centro_desde){
          document.getElementById("errorCentro").textContent = "Hasta debe ser mayor que Desde";
        }else{
          document.getElementById("errorCentro").textContent = "";
        }
        rellenarEmpleados();
      }

      function ajustarCentroHasta(valor){
        document.getElementById("selectCentroHasta").value = valor;
        comprobarCentroHastaMayor(document.getElementById("selectCentroHasta").value  );

      }

      function rellenarSelectCentros(valor){
        var empresa = valor.split('-')[0];
        var cif = valor.split('-')[1];

        var centros = <?php echo json_encode($all_centros); ?>;
        var cont = 0;

        if(valor != '0'){
          document.getElementById("selectCentroDesde").options.length=0; 
          document.getElementById("selectCentroHasta").options.length=0; 

          document.getElementById("selectCentroDesde").options[cont]=new Option("", "".concat('-', valor));
          document.getElementById("selectCentroHasta").options[cont]=new Option("", "".concat('-', valor));

          for(var i = 0; i < centros.length; i++){
            if(
              centros[i]["CIF"] == cif &&
              centros[i]["EMP"] == empresa
            )
            {
              cont++;
              document.getElementById("selectCentroDesde").options[cont]=new Option((centros[i]["NOM"]).concat(' (', centros[i]['COD'] + ')'), (centros[i]["NOM"]).concat('-', valor));
              document.getElementById("selectCentroHasta").options[cont]=new Option((centros[i]["NOM"]).concat(' (', centros[i]['COD'] + ')'), (centros[i]["NOM"]).concat('-', valor));
            }
          }
        }
        rellenarEmpleados();
      }

      function cargarSelectEmpresa(valor){
        if(valor != '0'){

          document.getElementById("selectEmpresa").options.length=0; 

          var cont = 0;
          var ultima_empresa = [];
          var users = <?php echo json_encode($all_users); ?>;

          document.getElementById("selectEmpresa").options[cont]=new Option("", "");

          for(var i = 0; i < users.length; i++){
            if(
                (users[i]["CIF"]) == valor && 
                (!ultima_empresa.includes((users[i])["EMP"]))
              )
              {
                cont = cont + 1;
                document.getElementById("selectEmpresa").options[cont]=new Option((users[i]["EMP"]), (users[i]["EMP"]).concat('-', valor));
                ultima_empresa.push((users[i])["EMP"]);
              }
          }
        }else{
          document.getElementById("selectEmpresa").options.length=0; 
        }
      }

      function comprobarValidezMes(){
        if(document.getElementById("desdeMes").value == 0 && document.getElementById("hastaMes").value != 0){
          document.getElementById("erorMes").textContent = "Añada un desde";
        }else{
          document.getElementById("erorMes").textContent = "";
        }
        desactivarBoton();
      }
          
      function ComboAño(){
        var d = new Date();
        var n = d.getFullYear();
        var select = document.getElementById("desdeAño");
        var select1 = document.getElementById("hastaAño");
        $cont = 1;
        for(var i = n; i>=1900; i--){
            select.options[$cont]= new Option(i, i);
            select1.options[$cont]= new Option(i, i);
            $cont = $cont + 1;
        }
      }

      function comprobarValidezAño(){
        if(document.getElementById("desdeAño").value > document.getElementById("hastaAño").value){
          document.getElementById("erorAño").textContent = "El segundo año debe ser mayor";
        }else if(document.getElementById("desdeAño").value == 0){
          document.getElementById("erorAño").textContent = "Añade un desde";
        }else{
          document.getElementById("erorAño").textContent = "";
        }

        desactivarBoton();
      }

      
    </script>

    <div class="center">
    @if($turno != null)
      <strong>Turno: {{$turno}}.</strong>
    @endif
    </div>
    @if($result_centro->fetch_array(MYSQLI_NUM)!=null)
      <form method="GET" action="{{route('marcajes.create',['centro'])}}" id="selector" enctype="multipart/form-data">
      @csrf
      <input id="ubicacion" name="ubicacion" hidden></input>  
      <div class="center">
        <strong>Centro: </strong> 
      </div>

        <select class="form-control{{ $errors->has('centro') ? ' is-invalid' : '' }}" id="centros" name="centros" required>
          @if ($cen1 != null)
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
        <div class="center">
          <label for="nota">
            Nota
          </label>
        </div>
        <div class="center">
          <textarea id="nota" name="nota" style="padding:5px;width: 400px;height: 120px;"></textarea>
        </div>
        
                 
    </form>
    <div id="clockdate">
      <div class="clockdate-wrapper">
        <div id="clock"></div>
        <div id="date"></div>
    </div>

    <?php
      $ultimo_marcaje = Marcajes::where('id_worker', '=', $crow['id'])->get()->toArray();
      if(!sizeof($ultimo_marcaje)==0){
        $ultimo_marcaje = $ultimo_marcaje[sizeof($ultimo_marcaje)-1];
      }
    ?>
    
    <div class="center">
      @if($ultimo_marcaje != null && $ultimo_marcaje['exit'] == null)
      <button type="submit" form="selector" style="background:#F9A958;border: 1px solid #E2870C;padding:10px;margin:10px;border-radius:20px;width:100px;height:100px">Salida</button>
      @else
      <button type="submit" form="selector" style="background:#F9A958;border: 1px solid #E2870C;padding:10px;margin:10px;border-radius:20px;width:100px;height:100px">Entrada</button>
      @endif
    </div>
      @endif 
    <div class="scrollH">
    <table id="myTable">
      <thead>
        @if(isset($marcajes))
          @if($marcajes)
            <tr class="principal">
              @if(!$movil)
              <td><strong>Productor</strong></td>
              <td><strong>Tipo</strong></td>
              @endif
              <td><strong>Fecha<br> Entrada</strong></td>
              <td><strong>Hora <br>Entrada</strong></td>
              <td><strong>Fecha<br> Salida</strong></td>
              <td><strong>Hora<br> Salida</strong></td>
              <td><strong>Nota entrada</strong></td>
              <td><strong>Nota salida</strong></td>
              <td><strong>Centro-<br>Empresa</strong></td>
              @if($crow['role'] == 'supervisor'||$crow['role'] == 'jefe')
              <td><strong>CIF</strong></td>
              @endif
              @if($crow['role'] == 'supervisor')
                <td><strong>Horas Totales</strong></td>
                <td><strong></strong></td>
                <td><strong></strong></td>
              @endif
            </tr>
          @endif
        @endif
      </thead>

      <tbody>
        @if(isset($marcajes))
          @foreach($marcajes as $index)
            @foreach($index as $a)
              <tr>
                @if(!$movil)
                  @if($use->role == 'empleado')
                    <td><strong>{{$use->NOM}}</strong></td>
                  @else
                    @foreach($users as $user)
                        @if($user->id == $a['id_worker'])
                            <td><strong>{{$user->NOM}}</strong></td>
                        @endif
                    @endforeach
                  @endif

                  <td>{{$a['nature_of_work']}}</td>
                @endif

                <td>{{$a['entrance']}}</td>

                @if($use->role == 'empleado')
                  @if (($a['check_in_time'])>=$use->timetable_entrance and ($a['check_in_time']<=$courtesy_entrance))
                    <td>{{$a['check_in_time']}}</td>
                  @else
                    <td style="color:#FF0000">{{$a['check_in_time']}}</td>
                  @endif
                @else
                  @foreach($users as $user)
                    @if($user->id == $a['id_worker'])
                      @if (($a['check_in_time'])>=$user->timetable_entrance and ($a['check_in_time']<=$courtesy_entrance))
                        <td>{{$a['check_in_time']}}</td>
                      @else
                        <td style="color:#FF0000">{{$a['check_in_time']}}</td>
                      @endif
                    @endif
                  @endforeach
                @endif

                <td>{{$a['exit']}}</td>

                @if($use->role == 'empleado')
                  @if (($a['departure_time'])>$use->timetable_exit and (($a['departure_time']<$courtesy_exit)))
                    <td>{{$a['departure_time']}}</td>
                  @else
                    <td style="color:#FF0000">{{$a['departure_time']}}</td>
                  @endif
                @else
                  @foreach($users as $user)
                    @if($user->id == $a['id_worker'])
                      @if (($a['departure_time'])>$user->timetable_exit and ($a['departure_time']<$courtesy_exit))
                        <td>{{$a['departure_time']}}</td>
                      @else
                        <td style="color:#FF0000">{{$a['departure_time']}}</td>
                      @endif
                    @endif
                  @endforeach
                @endif

                @if($a['entrance_note']==null)
                  <td>No hay nota</td>
                @else
                  <td style="text-align:center">
                    {{$a['entrance_note']}}
                  </td>
                @endif

                  @if($a['exit_note']==null)
                  <td>No hay nota</td>
                @else
                  <td style="text-align:center">
                    {{$a['exit_note']}}
                  </td>
                @endif

                <td>{{$a['CEN']}}-{{$a['EMP']}}</td>
                @if($crow['role'] == 'supervisor'||$crow['role'] == 'jefe')
                <td>{{$a['CIF']}}</td>
                @endif
                @if ($use->available and !$use->on_holidays)
                @if($crow['role'] == 'supervisor')
                  @if($a['departure_time']==null)
                  <td></td>
                  @else
                  <td>{{$a['totalHoras']}}</td>
                  @endif
                  <td><a href="" data-toggle="modal" data-target="#editModal{{ $a['id'] }}" class="btn btn-primary">
                  <span class="glyphicon glyphicon-pencil"></span>
                @endif
                
                </a>
                @else
                <td><a href="" data-toggle="modal" data-target="#noEdit" class="btn btn-secondary"><span class="glyphicon glyphicon-pencil"></span></a>
                
                <div class="modal fade" id="noEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-sm modal-notify modal-danger" role="document">
                          <!--Content-->
                          <div class="modal-content text-center">
                            <!--Header-->
                            <div class="modal-header d-flex justify-content-center btn-danger">
                            @if (!$use->available)
                              <p class="heading"><strong>Productor no disponible.<br></strong>No será posible Añadir ni Editar Marcaje hasta que se reincorpore.</strong></p>
                            @elseif ($use->on_holidays)
                              <p class="heading"><strong>Productor de vacaciones.<br></strong>No será posible Añadir ni Editar Marcaje hasta que se reincorpore.</strong></p>
                            @endif
                            </div>

                            <!--Body-->
                            <div class="modal-body">

                              <i class="fas fa-times fa-4x animated rotateIn equis"></i>
                              
                            </div>

                            <!--Footer-->
                            <div class="modal-footer flex-center">
                              <a href="" class="btn btn-danger waves-effect">Entendido</a> 
                            </div>
                          </div>
                          <!--/.Content-->
                        </div>
                      </div>
                @endif
                <!-- Modal -->
                <div class="modal fade" id="editModal{{ $a['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Modificar Marcaje</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                        <form method="POST" action="{{ route('marcajes.update', ['id' => $a['id']]) }}" id="edit{{ $a['id'] }}" enctype="multipart/form-data" onSubmit="arrayImg({{ $a['id'] }})">
                          @csrf
                          @method('PUT')

                          <div class="form-group row">
                              <label for="entrance" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de entrada:') }}</label>

                              <div class="col-md-6">
                                  <input id="entrance" type="date" value="{{ $a['entrance'] }}" class="form-control{{ $errors->has('entrance') ? ' is-invalid' : '' }}" name="entrance" value="{{ old('entrance') }}" required autofocus>

                                      @if ($errors->has('entrance'))
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $errors->first('entrance') }}</strong>
                                          </span>
                                      @endif
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="check_in_time" class="col-md-4 col-form-label text-md-right">{{ __('Entrada') }}</label>

                              <div class="col-md-6">
                              <input id="check_in_time" type="time" class="form-control{{ $errors->has('check_in_time') ? ' is-invalid' : '' }}" name="check_in_time" value="{{ $a['check_in_time'] }}" required>

                                  @if ($errors->has('check_in_time'))
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $errors->first('check_in_time') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>

                          <div class="form-group row">
                            <label for="exit" class="col-md-4 col-form-label text-md-right">{{ __('Fecha de salida') }}</label>

                            <div class="col-md-6">
                                <input id="exit" type="date" value="{{ $a['exit'] }}" class="form-control{{ $errors->has('exit') ? ' is-invalid' : '' }}" name="exit" value="{{ old('exit') }}" autofocus>

                                    @if ($errors->has('exit'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('exit') }}</strong>
                                        </span>
                                    @endif
                            </div>
                          </div>

                          <div class="form-group row">
                              <label for="departure_time" class="col-md-4 col-form-label text-md-right">{{ __('Salida') }}</label>

                              <div class="col-md-6">
                              <input id="departure_time" type="time" class="form-control{{ $errors->has('departure_time') ? ' is-invalid' : '' }}" name="departure_time" value="{{ $a['departure_time'] }}">

                                  @if ($errors->has('departure_time'))
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $errors->first('departure_time') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="exit_note" class="col-md-4 col-form-label text-md-right">{{ __('Nota') }}</label>
          
                              <div class="col-md-6">
                              <textarea class="form-control{{ $errors->has('exit_note') ? ' is-invalid' : '' }}" id="exit_note" rows="3" name="exit_note" >{{ $a['exit_note'] }}</textarea>

                                  @if ($errors->has('exit_note'))
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $errors->first('exit_note') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="CEN" class="col-md-4 col-form-label text-md-right">{{ __('Centro: ') }}</label>
          
                              <div class="col-md-6">
                              <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="3" id="CEN" type="text" class="form-control{{ $errors->has('CEN') ? ' is-invalid' : '' }}" name="CEN" value="{{ $a['CEN'] }}" required>
                                  @if ($errors->has('CEN'))
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $errors->first('CEN') }}</strong>
                                      </span>
                                  @endif
                              </div>
                          </div>

                        </form>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          <button type="submit" class="btn btn-primary" form="edit{{ $a['id'] }}">Guardar Cambios</button>
                        </div>
                      </div>
                    </div>
                </div>
                </td>
                @if($crow['role'] == 'supervisor')
                <td>          <!-- Button trigger modal-->
                  
                    <button class="btn btn-danger" data-toggle="modal" data-target="#modalConfirmDelete{{$a['id']}}">
                        <span class="glyphicon glyphicon-trash"></span> 
                    </button>
                      

                      <!--Modal: modalConfirmDelete-->
                      <div class="modal fade" id="modalConfirmDelete{{$a['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-sm modal-notify modal-danger" role="document">
                          <!--Content-->
                          <div class="modal-content text-center">
                            <!--Header-->
                            <div class="modal-header d-flex justify-content-center btn-danger">
                              <p class="heading"><strong>¿Eliminar marcaje?<br></strong>No será posible recuperarlo.</strong></p>
                            </div>

                            <!--Body-->
                            <div class="modal-body">

                              <i class="fas fa-times fa-4x animated rotateIn equis"></i>
                              <form action="{{ route('marcajes.destroy', $a['id'])}}" method="POST" id="formDelete{{$a['id']}}">
                                  @csrf   
                                  @method('DELETE')                                 
                              </form>  
                            </div>

                            <!--Footer-->
                            <div class="modal-footer flex-center">
                              
                              <button type="submit" class="btn  btn-outline-danger" form="formDelete{{$a['id']}}">Si</button>
                              <a href="" class="btn btn-danger waves-effect">No</a> 
                            </div>
                          </div>
                          <!--/.Content-->
                        </div>
                      </div>
                      <!--Modal: modalConfirmDelete-->
                      </td>
                      
                  @endif
              </tr>
            @endforeach
          @endforeach
        @endif
      </tbody>
    </table>

    <div class="center">
      <a href="{{ route('users.index')}}" class="enlace_alt">
        <div class="pop_alt">
          Volver al Menú
        </div>
      </a>
    </div>
  </div>
  </div>

  </div>
</div>
  </div>
</body>
@endsection
@section('script')
<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      
    });
  });
</script>
@endsection