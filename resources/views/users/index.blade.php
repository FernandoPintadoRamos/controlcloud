@extends('layouts.app')

@section('content')
  <script>
    function openNav() {
      document.getElementById("mySidenav").style.width = "240px";
      document.getElementById("main").style.marginLeft = "240px";
    }

    /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
    function closeNav() {
      document.getElementById("mySidenav").style.width = "0";
      document.getElementById("main").style.marginLeft = "0";
      document.body.style.backgroundColor = "white";
    }
  </script>

  <?php
    use App\User;
    use App\Empresa;
    use App\Image;
  ?>
  
  <style>
    .uper {
      margin-top: 50px;
    }

    body {
      font-family: "Lato", sans-serif;
      transition: background-color .5s;
    }

    .nav:hover > li > ul {
				display:block;
		}

    .sidenav {
      height: 100%;
      width: 0;
      position: fixed;
      z-index: 1;
      bottom: 0;
      left: 0;
      background-color: #F29B22;
      overflow-x: hidden;
      transition: 0.5s;
      padding-top: 60px;
      margin-top:120px
    }

    .sidenav a {
      padding: 8px 8px 8px 32px;
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
      transition: 0.3s;
    }

    .sidenav a:hover {
      color: #f1f1f1;
    }

    .sidenav .closebtn {
      position: absolute;
      top: 0;
      right: 25px;
      font-size: 36px;
      margin-left: 50px;
    }

    .perfil{
      border: 1px solid #E2870C;
      padding: 10px;
      border-top-right-radius: 20px;
      border-bottom-left-radius: 20px;
      background-color: #FBC375;
      font-size:15px;
      cursor:pointer;
      margin:20px;
      float: left;
    }

    .perfil:hover{
      background-color: #F7AB40;
      transition: background-color 500ms;
    }

    .main{
      background-color: #FCD49D;
      border: 1px solid #E2870C;
      height: auto;
      min-width: 400px;
      margin: 20px;
      padding: 20px;
      border-top-right-radius: 50px;
      border-bottom-left-radius: 50px;
      float:left;
      transition: all .3s ease-in-out;
    }

    .foto_perfil{
      width: 150px;
      height: 150px;
      border-radius: 50%;
      transition: all .3s ease-in-out;
    }

    .foto_perfil_supervisor{
      width: 150px;
      height: 150px;
      border-radius: 50%;
    }

    .foto_perfil:hover{
      opacity: 0.50;
      -webkit-transition: opacity 500ms;
      -moz-transition: opacity 500ms;
      -o-transition: opacity 500ms;
      -ms-transition: opacity 500ms;
      transition: opacity 500ms;
      transform: scale(0.75);
    }

    .enlace{
      height: 40px;
      width: 190px;
      color:#E2870C;
      border: 3px solid #E2870C;
      border-radius: 5px;
      float:left;
      transition: color 1000ms;
    }

    .enlace:hover{
      color: white;
      background-color: #E2870C;
      transition: background-color 1000ms;
      transition: color 1000ms;
    }

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

    .tarjeta:hover >ul >li > ul{
      transform: scale(1.1);
      background-color: #F9A958;
      transition: background-color 500ms;
      display:block;
    }

    .center{
      display: flex;
      justify-content: center;
    }

    #myProgress {
      width: 100%;
      background-color: #F2B173;
    }

    #myBar {
      width: 1%;
      height: 30px;
      background-color: #F18926;
    }
  </style>

  <div id="main">
    <div class="center">
      <span class="perfil" onclick="openNav()">&#9776;   PERFIL</span>
    </div>
    <div class="center">

    

      @if(Auth::user()->role=='supervisor')
      <div class="tarjeta">
        <div style="float:right">
          <img height=50px width=50px src="{{asset('/featureds/usuario-de-perfil.png')}}">
        </div>
        <div style="min-height:160px">
          <strong style="color:black">Administración</strong> </br>
          <p style="color:black">Datos personales de los trabajadores y de los centros asi como de los horarios.</p>
        </div>
          <ul class="nav" style="margin:0px;padding:0px">
            <li><p></p>
              <ul>
                <li><a href="#" data-toggle="modal" data-target="#selectModal" >Administrar perfiles</a></li>
                <li><a href="{{route('centros')}}" >Administrar centros</a></li>
              </ul>
            </li>
          </ul>
      </div>
      @endif

      @if(Auth::user()->role=='empleado')
      <a href="{{ route('misMarcajes') }}">
      <div class="tarjeta">
        <div style="float:right">
          <img height=50px width=50px src="{{asset('/featureds/firma.png')}}">
        </div>
        <div style="min-height:160px">
          <strong style="color:black">Marcajes</strong> </br>
          <p style="color:black">Registro de todos los marcajes.</p>
        </div>
      </div>  
      </a>
      @else
      @endif

      <a href="{{ route('absentismos') }}">
      <div class="tarjeta">
        <div style="float:right">
          <img height=50px width=50px src="{{asset('/featureds/firma.png')}}">
        </div>
        <div style="min-height:160px">
          <strong style="color:black">Vacaciones y permisos</strong> </br>
          <p style="color:black"></p>
        </div>
      </div>  
      </a>

      @if(Auth::user()->role=='supervisor' || Auth::user()->role=='jefe')
      <div class="tarjeta">
        <div style="float:right">
          <img height=50px width=50px src="{{asset('/featureds/firma.png')}}">
        </div>
        <div style="min-height:160px">
          <strong>Marcajes</strong> </br>
          Registro de los marcajes de todos los empleados y un registro de las horas que cada trabajador echa al dia.
        </div>
        <ul  class="nav" style="margin:0px;padding:0px">
          <li><p></p>
            <ul>
                <li><a href="{{ route('misMarcajes') }}">Ver marcajes</a></li>
                <li><a href="{{ route('registros') }}">Ver registro de horas</a></li>
            </ul>
          </li>
        </ul>
      </div>  
      @endif

    </div>
    <div class="center">
    <a href="{{ route('documents.index') }}">  
      <div class="tarjeta">
        <div style="float:right">
          <img height=50px width=50px src="{{asset('/featureds/google-docs.png')}}">
        </div>
        <div style="min-height:160px">
          <strong style="color:black">Gestión documental</strong> </br>
          <p style="color:black">Gestión de todos los documentos, con la posibilidad de subir archivos al servidor.</p>
        </div>
      </div>
    </a>

      @if(Auth::user()->role=='supervisor')
      <div class="tarjeta">
        <div style="float:right">
          <img height=50px width=50px src="{{asset('/featureds/extension-de-formato-de-archivo-csv.png')}}">
        </div>
        <div style="min-height:160px">
          <strong>Importación de ficheros</strong> </br>
          Dar de alta centros, empleados y horarios personales mediante archivos CSV.  
          </div>    
          <ul class="nav" style="margin:0px;padding:0px">
            <li><p></p>
              <ul>
                <li><a href="#" data-toggle="modal" data-target="#importCSVCentros">Importar CSV de centros</a></li>
                <li><a href="#" data-toggle="modal" data-target="#importCSV">Importar CSV de empleados</a></li>
                <li><a href="#" data-toggle="modal" data-target="#actualizarEmpleados">Importar CSV de horarios</a></li>
              </ul>
            </li>
          </ul>
      </div>
    </div>
    <div class="center">
      <div class="tarjeta">
        <div style="float:right">
          <img height=50px width=50px src="{{asset('/featureds/trabajo-en-equipo.png')}}">
        </div>
        <div style="min-height:160px">
          <strong>Dar de alta</strong> </br>
          Dar de alta a empleados de forma manual.    
        </div>    
          <ul class="nav" style="margin:0px;padding:0px">
            <li><p></p>
              <ul>
              <li><a href="#" data-toggle="modal" data-target="#darAlta">Dar de alta a trabajadores</a></li>
              </ul>
            </li>
          </ul>
      </div>    
      @endif

    </div>
  </div>

  
  <?php
    $imagen = Image::where('id_worker', Auth::user()->id)->first();
    if($imagen != null){
      $nombre = $imagen->img;
      $ruta = '../storage/app/documents/'.$nombre;
    }
  ?>

  <div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
      @foreach($users as $index)
      @foreach($index as $a)
      @if($a['imagenes'] == '[]')
        <img src="" alt="Elegir imagen de perfil">
      @else
      <div>
        <div class="carousel-inner">
          @isset($nombre)
          <img class="foto_perfil_supervisor" src="{{asset($ruta)}}">
          @endisset
      </div>  
      @endif
      @endforeach
      @endforeach
      </div>
      </br>
      <div>
        <a style="font-size: 15px;color:white"class="enlace" href="#" data-toggle="modal" data-target="#editModal{{ $a['id'] }}">Cambiar foto de perfil</a>
      </div>
      <div style="float:left;padding:10px">
        <p style="color:white"><strong>Nombre: </strong> {{Auth::user()->NOM}} </br></p>
        <p style="color:white"><strong>Primer Apellido: </strong> {{Auth::user()->AP1}} </br></p>
        <p style="color:white"><strong>Segundo Apellido: </strong> {{Auth::user()->AP2}} </br></p>
        </br>
        <p style="color:white"><strong>DNI:</strong> {{Auth::user()->DNI}}</br></p>
        <p style="color:white"><strong>Email:</strong> {{Auth::user()->email}}</br></p>
        <p style="color:white"><strong>Telefono:</strong> ...</br></p>
        <p style="color:white"><strong>Ubicación:</strong> ...</br></p>
      </div>
  </div>
  <?php
  $users = User::all();
  $num = sizeof($users);
?>
  <!--Modal para dar de alta a empleados-->
  <div class="modal fade" id="darAlta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Dar de alta. ({{$num}}/100)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                      <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="cod" class="col-md-4 col-form-label text-md-right">{{ __('Código:') }}</label>

                            <div class="col-md-6">
                            <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="5" id="cod" type="text" class="form-control{{ $errors->has('cod') ? ' is-invalid' : '' }}" name="cod" value="{{ old('cod') }}" required autofocus>

                                @if ($errors->has('cod'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cod') }}</strong>
                                    </span>
                                @endif

                                
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nom" class="col-md-4 col-form-label text-md-right">{{ __('Nombre:') }}</label>
                            
                            <div class="col-md-6">
                                <input maxlength="20" id="nom" type="text" class="form-control{{ $errors->has('nom') ? ' is-invalid' : '' }}" name="nom" value="{{ old('nom') }}" required autofocus>
                                   
                                    @if ($errors->has('nom'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('nom') }}</strong>
                                        </span>
                                    @endif
                                    
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <label for="ap1" class="col-md-4 col-form-label text-md-right">{{ __('Primer apellido:') }}</label>

                            <div class="col-md-6">
                            <input maxlength="20" id="ap1" type="text" class="form-control{{ $errors->has('ap1') ? ' is-invalid' : '' }}" name="ap1" required>

                                @if ($errors->has('ap1'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('ap1') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="ap2" class="col-md-4 col-form-label text-md-right">{{ __('Segundo apellido:') }}</label>
        
                            <div class="col-md-6">
                            <input maxlength="20" id="ap2" class="form-control{{ $errors->has('ap2') ? ' is-invalid' : '' }}" rows="3" name="ap2" required>

                                @if ($errors->has('ap2'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('ap2') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                          <label for="cen1" class="col-md-4 col-form-label text-md-right">{{ __('Centro 1:') }}</label>

                          <div class="col-md-6">
                              <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="3" id="cen1" type="text" class="form-control{{ $errors->has('cen1') ? ' is-invalid' : '' }}" name="cen1" required autofocus>

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
                              <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="3" id="cen2" type="text" class="form-control{{ $errors->has('cen2') ? ' is-invalid' : '' }}" name="cen2" autofocus>

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
                              <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="3" id="cen3" type="text" class="form-control{{ $errors->has('cen3') ? ' is-invalid' : '' }}" name="cen3" autofocus>

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
                              <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="3" id="cen4" type="text" class="form-control{{ $errors->has('cen4') ? ' is-invalid' : '' }}" name="cen4" autofocus>

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
                              <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="3" id="cen5" type="text" class="form-control{{ $errors->has('cen5') ? ' is-invalid' : '' }}" name="cen5" autofocus>

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
                            <input maxlength="9" id="dni" type="text" class="form-control{{ $errors->has('dni') ? ' is-invalid' : '' }}" name="dni" required>

                                @if ($errors->has('dni'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('dni') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="emp" class="col-md-4 col-form-label text-md-right">{{ __('Empresa:') }}</label>
        
                            <div class="col-md-6">
                            <input onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" maxlength="4" type="text" id="emp" class="form-control{{ $errors->has('emp') ? ' is-invalid' : '' }}" rows="3" name="emp" required>

                                @if ($errors->has('emp'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('emp') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cif" class="col-md-4 col-form-label text-md-right">{{ __('CIF:') }}</label>
        
                            <div class="col-md-6">
                            <input maxlength="9" type="text" id="cif" class="form-control{{ $errors->has('cif') ? ' is-invalid' : '' }}" rows="3" name="cif" required>

                                @if ($errors->has('cif'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cif') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="mail" class="col-md-4 col-form-label text-md-right">{{ __('Mail:') }}</label>
        
                            <div class="col-md-6">
                            <input type="email" id="mail" class="form-control{{ $errors->has('mail') ? ' is-invalid' : '' }}" rows="3" name="mail" required>

                                @if ($errors->has('mail'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('mail') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pwd" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña:') }}</label>
        
                            <div class="col-md-6">
                            <input type="password" id="pwd" class="form-control{{ $errors->has('mail') ? ' is-invalid' : '' }}" rows="3" name="pwd" required>

                                @if ($errors->has('pwd'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('pwd') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="rol" class="col-md-4 col-form-label text-md-right">{{ __('Rol:') }}</label>
        
                            <div class="col-md-6">
                            <!--<input type="text" id="rol" class="form-control{{ $errors->has('rol') ? ' is-invalid' : '' }}" rows="3" name="rol" required>-->
                              <input type="checkbox" id="rol" name="rol" value="supervisor">
                              <label for="rol"> Jefe</label><br>
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" style="background-color:#f6993f;border-color:#f6993f">
                                    {{ __('Enviar') }}
                                </button>
                            </div>
                        </div>

                    </form>      
                      </div>
                      
                    </div>
                  </div>
              </div>
  <!--Modal para dar de alta a empleados-->


  <!--Modal para los ficheros de los centros-->
  <div>
        <div class="modal fade" id="importCSVCentros" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Dar de alta centros</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                      <form method="POST" action="{{ route('importCsvCentros') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="centros" class="col-md-4 col-form-label text-md-right">{{ __('Archivo:') }}</label>

                            <div class="col-md-6">
                            <input maxlength="5" id="centros" type="file" name="centros" value="{{ old('centros') }}" required autofocus>

                                @if ($errors->has('centros'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('centros') }}</strong>
                                    </span>
                                @endif

                                
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" style="background-color:#f6993f;border-color:#f6993f">
                                    {{ __('Enviar') }}
                                </button>
                            </div>
                        </div>
                    </form>      
                      </div>
                      
                    </div>
                  </div>
              </div>
        </div>
<!--Modal para los ficheros de los centros-->

<!--Modal para los ficheros de los empleados-->

<div>
        <div class="modal fade" id="importCSV" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Dar de alta empleados. ({{$num}}/100)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                      <form method="POST" action="{{ route('importCsv') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="fichero" class="col-md-4 col-form-label text-md-right">{{ __('Archivo:') }}</label>

                            <div class="col-md-6">
                            <input maxlength="5" type="file" id="fichero" name="fichero" value="{{ old('fichero') }}" required autofocus/>

                              @if ($errors->has('fichero'))
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $errors->first('fichero') }}</strong>
                                  </span>
                              @endif                             
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                               
                                <button type="submit" class="btn btn-primary" style="background-color:#f6993f;border-color:#f6993f" >
                                    {{ __('Enviar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>   
                    </div>
                  </div>
              </div>
        </div>
  <!--Modal para los ficheros de los empleados-->

  <!--Modal para los ficheros de horarios-->
  <div>
        <div class="modal fade" id="actualizarEmpleados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Importar horarios</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                      <form method="POST" action="{{ route('actualizarEmpleados') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="centros" class="col-md-4 col-form-label text-md-right">{{ __('Archivo:') }}</label>

                            <div class="col-md-6">
                            <input maxlength="5" id="centros" type="file" name="centros" value="{{ old('centros') }}" required autofocus>

                                @if ($errors->has('centros'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('centros') }}</strong>
                                    </span>
                                @endif

                                
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" style="background-color:#f6993f;border-color:#f6993f">
                                    {{ __('Enviar') }}
                                </button>
                            </div>
                        </div>
                    </form>      
                      </div>
                      
                    </div>
                  </div>
              </div>
        </div>
  <!--Modal para los ficheros de horarios-->
  <?php
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

  <!--Modal para administrar perfiles-->
  <div class="modal fade" id="selectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        @if(Auth::user()->role=='supervisor'||Auth::user()->role=='jefe')
                        <h5 class="modal-title" id="exampleModalLabel">Administrar Perfil</h5>
                        @else
                          <h5 class="modal-title" id="exampleModalLabel">Datos Perfil</h5>
                        @endif
                        
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <?php
                        $id_sup = Auth::user()->id;
                        $empresas = Empresa::where('id_supervisor', '=', $id_sup)->get()->all();
                        $array_cifs = array();

                        foreach($empresas as $emp){
                          array_push($array_cifs, $emp->CIF);
                        }
                      ?>
                      <div class="modal-body">
                      <form method="GET" action="{{ route('buscarUse') }}" enctype="multipart/form-data">
                      <label for="selectCif" class="col-md-4 col-form-label text-md-right">{{ __('CIF Empresa:') }}</label>
                      <select id='selectCif' onchange='cargarSelectEmpresa(this.value);' required> 
                          <option selected value="0"></option> 
                          @foreach($array_cifs as $cif)
                              <option value="{{$cif}}">{{$cif}}</option>
                          @endforeach
                      </select> 
                            <br>
                      <label id="labelEmpresa" for="selectEmpresa" class="col-md-4 col-form-label text-md-right" hidden>{{ __('Empresa:') }}</label>
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
                    </div>
                  </div>
              </div>
  <!--Modal para administrar perfiles-->

  <!--Modal Profile-->
   <div class="modal fade" id="editModal{{ $a['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Foto de Perfil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                      <form method="POST" action="{{ route('users.update', ['id' => $a["id"]]) }}" id="edit{{ $a['id'] }}" enctype="multipart/form-data" onSubmit="arrayImg({{ $a['id'] }})">
                        @csrf
                        @method('PUT')
                        <div class="row justify-content-md-center">
                            @foreach($a['imagenes'] as $i)
                            
                                <div class="col text-center">
                                <img src="{{asset('/featureds/'.$i['img'])}}" id="{{ $i['id'] }}" class="imgBorrado"><br>
                                    {{-- <a href="{% url 'borrarRespuesta' r.id %}"><button class="btn btn-danger btn-xs bottonBorrar offset-md-11"><i class="fas fa-times"></i></button></a> --}}
                                    <a onclick="borrar('{{ $i['id'] }}')"><i class="fas fa-times"></i></a>
                                </div>
                            
                            @endforeach
                        </div> <br><br>
                        <div class="form-group row offset-md-2">
                          <label for="exampleFormControlFile1">Imágenes</label>
                          <div class="col-md-10">
                              <input type="file" class="form-control-file" id="exampleFormControlFile1" accept="image/*" name="img[]">
                              @if ($errors->has('img'))
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $errors->first('img') }}</strong>
                                  </span>
                              @endif
                          </div>
                        </div> <br><br>
                        <input type="hidden" id="imgB{{ $a['id'] }}" name="imgBorrado">
                      </form>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" form="edit{{ $a['id'] }}" style="background-color:#f6993f;border-color:#f6993f">Guardar Cambios</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color:transparent;border-color:#B0B0B0"><strong style="color:#B0B0B0">Cerrar</strong></button>
                      </div>
                    </div>
                  </div>
              </div>
  <!--Modal Profile-->

  <!-- Mensaje de alerta -->
  <div class="container uper">
    @if(session()->get('success'))
      <div class="alert alert-success">
        {{ session()->get('success') }}  
      </div><br />
    @endif
  </div>
@endsection
@section('script')
<script type="text/javascript">
    var imgBorrado = new Array();
    function borrar(i){
        var img = document.getElementById(i);
        if(img.style.opacity == 0.5){
            img.style.opacity = 1;
            var deleted = imgBorrado.indexOf(i);
            imgBorrado.splice( deleted, 1 );
        }else{
            img.style.opacity = 0.5;
            imgBorrado.push(i);
        }
    }
    function arrayImg(id){
        var imgB = imgBorrado.toString();
        document.getElementById("imgB"+id).value = imgB;
        
    }
</script>
@endsection