@extends('layouts.app')

@section('content')
<style>
    .center{
        display: flex;
        justify-content: center;
    }
    .mes{
        width: 220px;
        height: 195px;
        border: 1px solid #E2870C;
        float: left;
        background-color: #FAC86C;
        margin: 20px;
    }	
    table { 
        width:100%;
        background-color: #FAC86C;

    }
    thead{
        background-color: #F7BA4A;
    }

    td{
        text-align:center;
        width: 50px;
    }
    .dia{
        
        width:50px;
    }

    .dia:hover{
        
        transform: scale(1.1);
        transition: background-color 500ms;
        cursor: pointer
    }
    button{
        border: 1px solid #E2870C;
        border-radius: 10px;
        padding: 3px;
    }

    button:hover{
        background-color:#F9A958;
        transform: scale(1.1);
        transition: all .3s ease-in-out;
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

    
    a{
        color: black;
    }

    a:hover{
        color: black;
        text-decoration: none;
    }
</style>



<?php

    $meses = array(
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        10,
        11,
        12
    );

    $nombre_meses = array(
        'ENERO',
        'FEBRERO',
        'MARZO',
        'ABRIL',
        'MAYO',
        'JUNIO',
        'JULIO',
        'AGOSTO',
        'SEPTIEMBRE',
        'OCTUBRE',
        'NOVIEMBRE',
        'DICIEMBRE',
    );
?>
<body>
    
    <div style="float:left;width:1040px">
        <div class="center">
            <h2>{{$año}}</h2>
        </div>
        @foreach($meses as $mes)
            <?php
                $semanas = array(
                    new SplFixedArray(7),
                    new SplFixedArray(7),
                    new SplFixedArray(7),
                    new SplFixedArray(7),
                    new SplFixedArray(7),
                    new SplFixedArray(7),
                );
                $fecha = '01-'.$mes.'-'.$año;
                $num_dias = date( 't', strtotime( $fecha ));
                
                $semana = 0;
                for($i = 1; $i <= $num_dias; $i++){
                    $fecha = $i.'-'.$mes.'-'.$año;
                    $dia_semana = date('N', strtotime( $fecha ));
                    $semanas[$semana][$dia_semana-1] = $i;
                    if($dia_semana == 7){
                        $semana++;
                    }
                }
            ?>
            <div class="mes">
                <div style="text-align: center;background-color: #FBAD1C;">
                    <strong >{{$nombre_meses[$mes-1]}}</strong>
                </div>
                <table>
                <thead>
                        <th>
                            <td>L</td>
                            <td>M</td>
                            <td>X</td>
                            <td>J</td>
                            <td>V</td>
                            <td>S</td>
                            <td>D</td>
                        </th>
                    </thead>
                </table>
                <table>
                    <tbody>
                        @foreach($semanas as $semana)
                            <tr>
                                @foreach($semana as $dia)
                                    @if($dia == null)
                                        <td></td>
                                    @else
                                        @if($dia.'-'.$mes.'-'.$año == date('j-n-Y'))
                                        <td id="{{$dia.'/'.$mes.'/'.$año}}" onclick="clickDia(this.id)"  class="dia" style="background-color: #EE9D07">
                                            {{$dia}}
                                        </td>
                                        @else
                                        <td id="{{$dia.'/'.$mes.'/'.$año}}"  onclick="clickDia(this.id)" class="dia">
                                            {{$dia}}
                                        </td>
                                        @endif
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
    <?php
        use App\User;
        use App\Empresa;
        use App\Absentismos;

        $all_users = User::all()->toArray();
        $all_empresas = Empresa::where('id_supervisor', Auth::user()->id)->get()->toArray();
        $all_cifs = array();
        $users = array();
        foreach($all_empresas as $emp){
            array_push($all_cifs, $emp['CIF']);
        }

        foreach($all_users as $u){
            if(in_array($u['CIF'], $all_cifs)){ 
                array_push($users, $u);
            }
        }

    ?>

    <?php
        $all_absentismos = Absentismos::all()->toArray();
    ?>
    <div style="float: left; margin-top: 40px">
        <br>
        
        <form method="GET" action="{{ route('cambAñoAdmin') }}" enctype="multipart/form-data">

            <label for="anio" class="col-md-4 col-form-label text-md-right">{{ __('AÑO:') }}</label>

            <select id="anio" name="anio" required>
                <option selected></option>
                @foreach(range(2000, intval(date('Y'))+5) as $anio)
                    <option value="{{ $anio }}">{{ $anio }}</option>
                @endforeach
            </select>

            <br>
            <br>
            <div class="center">
                <button type="submit">
                    Cambiar año
                </button>
            </div>
        </form>

        <br>

        <input id="mostrar" type="checkbox" onchange="mostrar()"> Buscar empleado
        <div id="verSolicitudes">
            <br>
            <label for="solicitudes"><strong>Solicitudes Pendientes</strong></label>
            <br>
            <select id="solicitudes" onchange="mostrarSol(this.value)">
                <option selected></option>
                @foreach ($all_absentismos as $abs)
                    <?php
                        $user = User::find($abs['id_worker']);
                        $fecha = intval(explode('-', $abs['hasta'])[0]);
                    ?>
                    @if ($fecha == $año && $abs['aceptado'] == 0)
                        <option value="{{ $abs['id'] }}">{{ $abs['tipo'].' '.$user->COD.' '.$user->NOM }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div id="buscarEmpleado" hidden>
            <label><strong>Seleccionar empleado:</strong></label>
            <br>
            <label>CIF</label>
            <select style="width: 122px" onchange="cargarEmpresa(this.value)">
                <option selected></option>
                @foreach ($all_cifs as $cif)
                    <option value="{{ $cif }}">{{ $cif }}</option>
                @endforeach
            </select>

            <br>

            <label>Empresa</label>
            <select id="select_empresa" style="width: 82px" onchange="cargarEmpleados(this.value)"></select>

            <br>

            <label>Empleado</label>
            <select id="select_empleado" style="width: 72px" onchange="cargarCalendario(this.value)"></select>
            
            <br>
            <br>

            <label><strong>Tipo:</strong></label>
            <br>
            <select id="select_tipo" onchange="pintarCaledario(this.value)">
                <option selected></option>
                <option value="permiso">Permiso</option>
                <option value="falta">Falta</option>
                <option value="vacaciones">Vacaciones</option>
                <option value="parte">Parte de IT</option>
            </select>
        </div>

        <br>

        <div id="solicitud" style="border: 1px solid #E2870C; padding: 10px" hidden>
            <strong>Solicitud:</strong>

            <br>
            <form method="GET" action="{{ route('modAbs') }}" enctype="multipart/form-data">
                <label>Desde:</label>
                <input style="width: 90px" id="fecha_desde" name="fecha_desde" disabled>
                <br>

                <label>Hasta:</label>&nbsp;
                <input style="width: 90px" id="fecha_hasta" name="fecha_hasta" disabled>
                <br>

                <input id="id_ab_mod" name="id_ab_mod" style="width: 50px" hidden>

                <button type="submit" id="boton_guardar" hidden>Guardar</button>
            </form>


            <label id="label_desc">Descripción:</label>
            <br>
            <input style="width: 150px" id="desc" disabled>
            <br>
            <br>
            <form method="GET" action="{{ route('aceptar') }}" enctype="multipart/form-data">
                
                <input id="id_ab_acp" name="id_ab_acp" style="width: 50px" hidden>
                <button type="submit" >Aceptar</button>
            </form>
            <br>
            <form method="GET" action="{{ route('rechazar') }}" enctype="multipart/form-data">

                <input id="id_ab_rec" name="id_ab_rec" style="width: 50px" hidden>
                <button type="submit">Rechazar</button>
            </form>

            <br>
            <button onclick="mod()">Modificar</button>
            
        </div>

        
        <br>     
        <a href="{{ route('volver')}}">
            <div class="pop">
                Volver
            </div>
        </a>
    </div>
</body>



<script>
    
    var all_use = [];
    var all_abs = [];

    var ver = 0;

    function mod(){
        if(ver == 1){
            document.getElementById('boton_guardar').hidden = true;
            document.getElementById('fecha_desde').disabled = true;
            document.getElementById('fecha_hasta').disabled = true;
            ver = 0;
        }else{
            document.getElementById('boton_guardar').hidden = false;
            document.getElementById('fecha_desde').disabled = false;
            document.getElementById('fecha_hasta').disabled = false;
            ver = 1;
        }
    }

    function mostrarSol(valor){
        // Pintamos todo a color original
        desde_fecha = new Date({{ $año }}, 0, 1);
        hasta_fecha = new Date({{ $año }}, 11, 31);
        var hoy = new Date();
        hoy = hoy.getDate()+'/'+(hoy.getMonth()+1)+'/'+hoy.getFullYear();
        while(hasta_fecha.getTime() >= desde_fecha.getTime()){
            add = desde_fecha.getDate()+'/'+(desde_fecha.getMonth()+1)+'/'+desde_fecha.getFullYear();

            document.getElementById(add).style.backgroundColor = '#FAC86C';

            if(add == hoy){
                document.getElementById(add).style.backgroundColor = '#EE9D07';
            }

            desde_fecha.setDate(desde_fecha.getDate() + 1);
            
            document.getElementById(add).style.opacity = 1;
        }
        var abs = <?php echo json_encode($all_absentismos); ?>;
        for(var i = 0; i < abs.length; i++){
            if(abs[i]['id'] == valor){
                var desde_fecha = new Date(abs[i]['desde']);
                var hasta_fecha = new Date(abs[i]['hasta']);

                while(hasta_fecha.getTime() >= desde_fecha.getTime()){
                    add = desde_fecha.getDate()+'/'+(desde_fecha.getMonth()+1)+'/'+desde_fecha.getFullYear();
                    document.getElementById(add).style.backgroundColor = '#36FF00';
                    if(abs[i]['aceptado'] == 0){
                        document.getElementById(add).style.opacity = 0.5;
                    }
                    desde_fecha.setDate(desde_fecha.getDate() + 1);
                }

                document.getElementById('fecha_desde').value = abs[i]['desde'];
                            document.getElementById('fecha_hasta').value = abs[i]['hasta'];
                            document.getElementById('id_ab_rec').value = abs[i]['id'];
                            document.getElementById('id_ab_acp').value = abs[i]['id'];
                            document.getElementById('id_ab_mod').value = abs[i]['id'];
                                
                            document.getElementById('desc').hidden = true;
                            if(abs[i]['descripcion'] != null){
                                document.getElementById('desc').value = abs[i]['descripcion'];
                                document.getElementById('desc').hidden = false;
                                document.getElementById('label_desc').hidden = false;
                            }else{
                                document.getElementById('desc').hidden = true;
                                document.getElementById('label_desc').hidden = true;
                            }
                            document.getElementById('solicitud').hidden = false;
            }
        }
    }

    function mostrar(){
        document.getElementById('solicitud').hidden = true;
         // Pintamos todo a color original
        var desde_fecha = new Date({{ $año }}, 0, 1);
        var hasta_fecha = new Date({{ $año }}, 11, 31);
        var hoy = new Date();
        hoy = hoy.getDate()+'/'+(hoy.getMonth()+1)+'/'+hoy.getFullYear();
        while(hasta_fecha.getTime() >= desde_fecha.getTime()){
            add = desde_fecha.getDate()+'/'+(desde_fecha.getMonth()+1)+'/'+desde_fecha.getFullYear();

            document.getElementById(add).style.backgroundColor = '#FAC86C';

            if(add == hoy){
                document.getElementById(add).style.backgroundColor = '#EE9D07';
            }

            desde_fecha.setDate(desde_fecha.getDate() + 1);
            
            document.getElementById(add).style.opacity = 1;
        }
        var x = document.getElementById("mostrar").checked;
        if(x){
            document.getElementById('buscarEmpleado').hidden = false;
            document.getElementById('verSolicitudes').hidden = true;
        }else{
            document.getElementById('buscarEmpleado').hidden = true;
            document.getElementById('verSolicitudes').hidden = false;
        }
    }

    function clickDia(valor){
        valor = valor.split('/');
        valor = new Date(valor[2], valor[1]-1, valor[0]);
        if(document.getElementById('select_empleado').value != '' && document.getElementById('select_tipo').value != ''){
            var tipo = document.getElementById('select_tipo').value;
            var empleado = document.getElementById('select_empleado').value;

            var abs = <?php echo json_encode($all_absentismos); ?>;
            for(var i = 0; i < abs.length; i++){
                var desde = new Date(abs[i]['desde']);
                var hasta = new Date(abs[i]['hasta']);
                if(abs[i]['tipo'] == tipo){
                    if(abs[i]['id_worker'] == empleado){
                        if(desde < valor && valor <= hasta){
                            document.getElementById('fecha_desde').value = abs[i]['desde'];
                            document.getElementById('fecha_hasta').value = abs[i]['hasta'];
                            document.getElementById('id_ab_rec').value = abs[i]['id'];
                            document.getElementById('id_ab_acp').value = abs[i]['id'];
                            document.getElementById('id_ab_mod').value = abs[i]['id'];
                            
                            if(abs[i]['descripcion'] != null){
                                document.getElementById('desc').value = abs[i]['descripcion'];
                                document.getElementById('desc').hidden = false;
                            }else{
                                document.getElementById('desc').hidden = true;
                            }
                            document.getElementById('solicitud').hidden = false;
                        }
                    }
                }
            }
        }
    }

    function pintarCaledario(valor){
        if(valor == ''){
            document.getElementById('solicitud').hidden = true;
        }
        var array = all_abs;
        // Pintamos todo a color original
        desde_fecha = new Date({{ $año }}, 0, 1);
        hasta_fecha = new Date({{ $año }}, 11, 31);
        var hoy = new Date();
        hoy = hoy.getDate()+'/'+(hoy.getMonth()+1)+'/'+hoy.getFullYear();
        while(hasta_fecha.getTime() >= desde_fecha.getTime()){
            add = desde_fecha.getDate()+'/'+(desde_fecha.getMonth()+1)+'/'+desde_fecha.getFullYear();

            document.getElementById(add).style.backgroundColor = '#FAC86C';

            if(add == hoy){
                document.getElementById(add).style.backgroundColor = '#EE9D07';
            }

            desde_fecha.setDate(desde_fecha.getDate() + 1);
            
            document.getElementById(add).style.opacity = 1;
        }

        for(var i = 0; i < array.length; i++){
            
            var desde = array[i]['desde'].split('-');
            
            desde_fecha = new Date(desde[0], desde[1]-1, desde[2]);
            desde = desde[2]+'/'+desde[1]+'/'+desde[0];

            var hasta = array[i]['hasta'].split('-');
            
            hasta_fecha = new Date(hasta[0], hasta[1]-1, hasta[2]);
            hasta = hasta[2]+'/'+hasta[1]+'/'+hasta[0];
            if(array[i]['tipo'] == valor && array[i]['id_worker'] == document.getElementById('select_empleado').value){
            while(hasta_fecha.getTime() >= desde_fecha.getTime()){
                add = desde_fecha.getDate()+'/'+(desde_fecha.getMonth()+1)+'/'+desde_fecha.getFullYear();
                document.getElementById(add).style.backgroundColor = '#36FF00';
                if(array[i]['aceptado'] == 0){
                    document.getElementById(add).style.opacity = 0.5;
                }
                desde_fecha.setDate(desde_fecha.getDate() + 1);
            }

            }
        }
    }

    function cargarCalendario(valor){
        
            if(valor == ''){
                
                document.getElementById('solicitud').hidden = true;
                // Pintamos todo a color original
                desde_fecha = new Date({{ $año }}, 0, 1);
                hasta_fecha = new Date({{ $año }}, 11, 31);
                var hoy = new Date();
                hoy = hoy.getDate()+'/'+(hoy.getMonth()+1)+'/'+hoy.getFullYear();
                while(hasta_fecha.getTime() >= desde_fecha.getTime()){
                    add = desde_fecha.getDate()+'/'+(desde_fecha.getMonth()+1)+'/'+desde_fecha.getFullYear();

                    document.getElementById(add).style.backgroundColor = '#FAC86C';

                    if(add == hoy){
                        document.getElementById(add).style.backgroundColor = '#EE9D07';
                    }

                    desde_fecha.setDate(desde_fecha.getDate() + 1);
                    
                    document.getElementById(add).style.opacity = 1;
                }
            }
            var abs = <?php echo json_encode($all_absentismos); ?>;
            for(var i = 0; i < abs.length; i++){
                if(abs[i]['id_worker'] == valor){
                    all_abs.push(abs[i]);
                }
            }

            if(document.getElementById('select_tipo').value != ''){
                pintarCaledario(document.getElementById('select_tipo').value);
            }
    }

    function cargarEmpleados(valor){

        if(valor != ''){
            document.getElementById('solicitud').hidden = true;

            document.getElementById("select_empleado").options.length=0;
            var cont = 0;
            var users = [];
            document.getElementById("select_empleado").options[cont]=new Option("", "");
            for(var i = 0; i<all_use.length; i++){
                if(all_use[i]['EMP'] == valor){
                    cont++;
                    users.push(all_use[i]);
                    document.getElementById("select_empleado").options[cont]=new Option(all_use[i]['COD'] + ' ' + all_use[i]['NOM'], all_use[i]['id']);
                }
            }
            all_use = users;
        }else{
            // Pintamos todo a color original
            desde_fecha = new Date({{ $año }}, 0, 1);
            hasta_fecha = new Date({{ $año }}, 11, 31);
            var hoy = new Date();
            hoy = hoy.getDate()+'/'+(hoy.getMonth()+1)+'/'+hoy.getFullYear();
            while(hasta_fecha.getTime() >= desde_fecha.getTime()){
                add = desde_fecha.getDate()+'/'+(desde_fecha.getMonth()+1)+'/'+desde_fecha.getFullYear();

                document.getElementById(add).style.backgroundColor = '#FAC86C';

                if(add == hoy){
                    document.getElementById(add).style.backgroundColor = '#EE9D07';
                }

                desde_fecha.setDate(desde_fecha.getDate() + 1);
                
                document.getElementById(add).style.opacity = 1;
            }
            document.getElementById("select_empleado").options.length=0;
        }
    }

    function cargarEmpresa(valor){
        if(valor != ''){
            document.getElementById('solicitud').hidden = true;

            var users = <?php echo json_encode($all_users); ?>;
            for(var i = 0; i < users.length; i++){
                if((users[i]["CIF"])==valor){
                    all_use.push(users[i]);
                }
            }
            document.getElementById("select_empresa").options.length=0;
            var emps = []; 
            var cont = 0;
            document.getElementById("select_empresa").options[cont]=new Option("", "");
            for(var v = 0; v < all_use.length; v++){
                if(!emps.includes(all_use[v]['EMP'])){
                    emps.push(all_use[v]['EMP']);
                    cont++;
                    document.getElementById("select_empresa").options[cont]=new Option(all_use[v]['EMP'], all_use[v]['EMP']);
                }
            }
        }else{
            // Pintamos todo a color original
            desde_fecha = new Date({{ $año }}, 0, 1);
            hasta_fecha = new Date({{ $año }}, 11, 31);
            var hoy = new Date();
            hoy = hoy.getDate()+'/'+(hoy.getMonth()+1)+'/'+hoy.getFullYear();
            while(hasta_fecha.getTime() >= desde_fecha.getTime()){
                add = desde_fecha.getDate()+'/'+(desde_fecha.getMonth()+1)+'/'+desde_fecha.getFullYear();

                document.getElementById(add).style.backgroundColor = '#FAC86C';

                if(add == hoy){
                    document.getElementById(add).style.backgroundColor = '#EE9D07';
                }

                desde_fecha.setDate(desde_fecha.getDate() + 1);
                
                document.getElementById(add).style.opacity = 1;
            }
            all_use = [];
            document.getElementById("select_empresa").options.length=0;
            document.getElementById("select_empleado").options.length=0;

        } 

        
    }
</script>
@endsection