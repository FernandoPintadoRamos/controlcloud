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
        margin: 20px
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
        background-color: #F9A958;
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

    <div style="float:left; margin-top: 60px">
        <form method="POST" action="{{ route('ponerFaltas') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <label><strong>Seleccionar empleado:</strong></label>
            <br>
            <label>CIF</label>
            <select style="width: 122px" name="select_cif" onchange="cargarEmpresa(this.value)">
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
            <select id="select_empleado" name="select_empleado" style="width: 72px"></select>

            <br>

            <label><strong>Desde - Hasta</strong></label>
            <br>
            <label for="fecha_desde">Desde</label>
            <input id="fecha_desde" name="fecha_desde" style="width: 120px" required>

            <br>
            <label for="fecha_hasta">Hasta</label>
            <input id="fecha_hasta" name="fecha_hasta" style="width: 120px" required>
            <br>

            <label for="tipo"><strong>Tipo:</strong></label>
            <br>
            <select id="tipo" name="tipo" onchange="generarIT()"  required>
                <option></option>
                <option value="#36FF00-permiso">Permiso</option>
                <option value="#FF0000-falta">Falta</option>
                <option value="#FF00FF-vacaciones">Vacaciones</option>
                <option value="#FF00FF-parte">Parte de IT</option>
            </select>
            <br>
            <label for="tipo_it" id="tipo_it_label" hidden>Tipo IT:</label>
            <br>
            <select id="tipo_it" name="tipo_it" style="width: 220px" hidden>
                <option></option>
                <option value="Enfermedad común">Enfermedad común</option>
                <option value="Enfermedad profesional">Enfermedad profesional</option>
                <option value="Accidente no laboral">Accidente no laboral</option>
                <option value="Accidente laboral">Accidente laboral</option>
                <option value="Observación enfermemdad">Observación enfermemdad</option>
                <option value="Maternidad/Paternidad">Maternidad/Paternidad</option>
                <option value="Maternidad/Paternidad sustituida">Maternidad/Paternidad sustituida</option>
                <option value="Pago directo">Pago directo</option>
                <option value="Riesgo">Riesgo</option>
                <option value="Riesgo sustituido">Riesgo sustituido</option>
            </select>
            <br>
            <label for="file">Fichero adicional</label>
            <br>
            <input type="file" id="file" name="file" style="width: 151px">

            <br>
            <br>
            <button type="submit">
                Asignar
            </button>
        </form>
        <br>
        <button onclick="borrarSel()">Borrar selección</button>
        <br>
        <br>
        <br>
        
        <a href="{{ route('volver')}}">
            <div class="pop">
                Volver
            </div>
        </a>
    </div>
</body>


<script>
    var click = 0;
    var fecha_desde = null;
    var fecha_hasta = null;
    var all_use = [];

    function generarIT(){
        var tipo_select = document.getElementById("tipo");
        var t = tipo_select.options[tipo_select.selectedIndex].value;
 
        var tipo = t.split('-')[1];

        var tipo_it = document.getElementById("tipo_it");
        var tipo_label = document.getElementById("tipo_it_label");
        tipo_it.hidden = false;
        tipo_label.hidden = false;

        if(tipo == 'parte'){
                
            tipo_it.hidden = false;
            tipo_label.hidden = false;
            tipo_it.required =true;
        }else{

            tipo_it.hidden = true;
            tipo_label.hidden = true;
            tipo_it.required =false;
        }
    }

    function clickDia(valor){

        if(document.getElementById('select_empleado').value != ''){
            if(click == 0){

                document.getElementById('fecha_desde').value = valor;
                fecha = valor.split('/');
                fecha_desde = new Date(fecha[2], fecha[1]-1, fecha[0]);
                click = 1;
                }else if(click == 1){

                fecha = valor.split('/');
                fecha_hasta = new Date(fecha[2], fecha[1]-1, fecha[0]);

                if(fecha_hasta.getTime()<fecha_desde.getTime()){
                    click = 1;
                }else{
                    document.getElementById('fecha_hasta').value = valor;

                    
                    while(fecha_hasta.getTime() >= fecha_desde.getTime()){
                        add = fecha_desde.getDate()+'/'+(fecha_desde.getMonth()+1)+'/'+fecha_desde.getFullYear();
                        document.getElementById(add).style.backgroundColor = 'red';
                        fecha_desde.setDate(fecha_desde.getDate() + 1);
                    }

                    click = 2;
                }
            }
        }else{
            alert('Elige un empleado')
        }
        
    }

    function borrarSel(){
        click = 0;
        fecha_desde = null;
        fecha_hasta = null;
        document.getElementById('fecha_desde').value = '';
        document.getElementById('fecha_hasta').value = '';

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

    function cargarEmpresa(valor){
        reset();

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
        
    }

    function cargarEmpleados(valor){
        reset();

        document.getElementById("select_empleado").options.length=0;
        var cont = 0;

        document.getElementById("select_empleado").options[cont]=new Option("", "");
        for(var i = 0; i<all_use.length; i++){
            if(all_use[i]['EMP'] == valor){
                cont++;
                document.getElementById("select_empleado").options[cont]=new Option(all_use[i]['COD'] + ' ' + all_use[i]['NOM'], all_use[i]['id']);
            }
        }
    }

    function reset(){
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
         
        click = 0;
        fecha_desde = null;
        fecha_hasta = null;
    }
</script>

@endsection