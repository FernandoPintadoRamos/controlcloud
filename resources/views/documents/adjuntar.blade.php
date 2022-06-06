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

<div class="center">
    <div class="tarjeta">
        <div class="titulo" style="background-color:#F7AB40">
            <h5><strong>Adjuntar Documento</strong></h5>
        </div>

        <div class="center">
            <div class="cuerpo">
                <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="form-group row offset-md-2">
                            <label for="exampleFormControlFile1">Documento</label>
                            <div class="col-md-6">
                                <input type="file" class="form-control-file" id="doc" accept="application/pdf" name="doc" multiple required>
                                @if ($errors->has('dba_close'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('dba_close') }}</strong>
                                    </span>
                                @endif
                            </div>

                    <?php
                        $conn = mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'),env('DB_DATABASE'));
                        $sql = "SELECT distinct(CIF), NOM_EMP FROM users";
                        $result = mysqli_query($conn, $sql);
                      ?>

                    Tipo:  
                    <select class="form-control{{ $errors->has('centro') ? ' is-invalid' : '' }}" id="tipo" name="tipo" style="margin:10px" required>
                    <option selected></option>
                        <option value="1">Nomina</option>
                        <option value="2">Certificado</option>
                        <option value="3">Documentacion</option>
                    </select>
                    <label for="selectCif" class="col-md-4 col-form-label text-md-right">{{ __('CIF Empresa:') }}</label>
                    <select style="margin:10px" id='selectCif' onchange='cargarSelectEmpresa(this.value);' required> 
                          <option selected value="0"></option> 
                          <?php while($row = mysqli_fetch_assoc($result)) {?>
                            @if($row['CIF']!=null)
                              <option value="{{$row['CIF']}}">{{$row['CIF'].' '.$row['NOM_EMP']}}</option>
                            @endif
                          <?php }?>
                      </select> 
                            <br>
                      <label id="labelEmpresa" for="selectEmpresa" class="col-md-4 col-form-label text-md-right" hidden>{{ __('Empresa:') }}</label>
                      <select style="margin:10px" id='selectEmpresa'  onchange='cargarSelectEmpleado(this.value);' hidden required> </select> 
                            <br>
                      <label id="labelEmpleado" for="selectEmpleado" class="col-md-4 col-form-label text-md-right" hidden>{{ __('Empleado:') }}</label>
                      <select id='selectEmpleado' name='selectEmpleado' hidden required> </select>

                    <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Descripcion') }}</label>

                            <div class="col-md-6">
                                <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" id="description" rows="3" name="description" required></textarea>

                                @if ($errors->has('description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
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
<!--
<div class="container">
        @if(session('message'))
            <div class="alert alert-{{ session('message')[0] }}"> {{ session('message')[1] }} </div> 
        @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Añadir Documento</div>

                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</div>
-->
@endsection