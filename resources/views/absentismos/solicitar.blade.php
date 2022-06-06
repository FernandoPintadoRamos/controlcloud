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

    .dia:hover{
        
      transform: scale(1.1);
      background-color: #F9A958;
      transition: background-color 500ms;
      cursor: pointer
    }

    a{
        color: black;
    }

    a:hover{
        color: black;
        text-decoration: none;
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

</style>

<script>
    var primera_vez = 0;
    var desde = null;
    var hasta = null;

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

    function borrarSel(){
        var fechas =[];
        primera_vez = 0;
        hasta_fecha = new Date(hasta[2], hasta[1]-1, hasta[0]);
        desde_fecha = new Date(desde[2], desde[1]-1, desde[0]);

        document.getElementById("fecha_hasta").value = '';
        document.getElementById("fecha_desde").value = '';

        while(hasta_fecha.getTime() >= desde_fecha.getTime()){
            //fechas.push(desde[0]+'/'+desde[1]+'/'+desde[2]);
            add = desde_fecha.getDate()+'/'+(desde_fecha.getMonth()+1)+'/'+desde_fecha.getFullYear();
            fechas.push(add);
            desde_fecha.setDate(desde_fecha.getDate() + 1);
        }
        
        for(var i = 0; i < fechas.length; i++){
            document.getElementById(fechas[i]).style.backgroundColor = '#FAC86C';
        }
    }

    function clickDia(valor){

        var tipo = document.getElementById("tipo");
        var t = tipo.options[tipo.selectedIndex].value;

        var color = t.split('-')[0];

        if(color != ''){
            var fechas = [];
            if(primera_vez == 0){
                primera_vez = 1;
                desde = valor.split('/');
                desde_fecha = new Date(desde[2], desde[1]-1, desde[0]);
                
                document.getElementById("fecha_desde").value = (valor);

            }else if(primera_vez == 1){
                primera_vez = 2;
                hasta = valor.split('/');
                hasta_fecha = new Date(hasta[2], hasta[1]-1, hasta[0]);

                

                if(hasta_fecha.getTime() < desde_fecha.getTime()){
                    primera_vez = 1;
                }else{
                    while(hasta_fecha.getTime() >= desde_fecha.getTime()){
                        document.getElementById("fecha_hasta").value = (valor);
                        add = desde_fecha.getDate()+'/'+(desde_fecha.getMonth()+1)+'/'+desde_fecha.getFullYear();
                        fechas.push(add);
                        desde_fecha.setDate(desde_fecha.getDate() + 1);
                    }
                    
                    for(var i = 0; i < fechas.length; i++){
                        document.getElementById(fechas[i]).style.backgroundColor = color;
                    }
                }
            }
        }else{
            alert('Seleccione un tipo');
        }

        
    }
</script>
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

        <form method="GET" action="{{ route('cambAño') }}" enctype="multipart/form-data">

            <label for="año" class="col-md-4 col-form-label text-md-right">{{ __('AÑO:') }}</label>

            <select id="año" name="año">
                @foreach(range(2000, intval(date('Y'))+5) as $año)
                    <option value="{{ $año }}">{{ $año }}</option>
                @endforeach
            </select>

            <br>
            
            <button type="submit">
                Cambiar año
            </button>
        </form>

        <br>

        <button onclick="borrarSel()">Borrar selección</button>

        <br>
        <br>
        

        <form method="POST" action="{{ route('envSol') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <label for="fecha_desde">Desde</label>
            <input id="fecha_desde" name="fecha_desde" style="width: 120px" required>

            <br>
            <label for="fecha_hasta">Hasta</label>
            <input id="fecha_hasta" name="fecha_hasta" style="width: 120px" required>

            <br>

            <label for="tipo">Tipo</label>
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
                Enviar solicitud
            </button>
        </form>
        <br>
        <a href="{{ route('volver')}}">
            <div class="pop">
                Volver
            </div>
        </a>
    </div>
</body>
@endsection