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
        transition: background-color 500ms;
        cursor: pointer
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
    use App\Absentismos;

    $absentismos = Absentismos::where('id_worker', Auth::user()->id)->get()->toArray();
?>

<script>

    function cargarCalendario(valor){
        
        var fechas = [];
        var array = <?php echo json_encode($absentismos); ?>;

        // Pintamos todo a color original
        desde_fecha = new Date({{ $año }}, 0, 1);
        hasta_fecha = new Date({{ $año }}, 11, 31);

        while(hasta_fecha.getTime() >= desde_fecha.getTime()){
            add = desde_fecha.getDate()+'/'+(desde_fecha.getMonth()+1)+'/'+desde_fecha.getFullYear();
            document.getElementById(add).style.backgroundColor = '#FAC86C';
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

            
            if(array[i]['tipo'] == valor){
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
    <div style="float: left;margin-top: 70px">
        <label for="select_tipo">Tipo</label>
        <select id="select_tipo" onchange="cargarCalendario(this.value)">
            <option></option>
            <option value="permiso">Permiso</option>
            <option value="falta">Falta</option>
            <option value="vacaciones">Vacaciones</option>
            <option value="parte">Parte de IT</option>
        </select>

        <br>
        <br>

        <a href="{{ route('volver')}}">
            <div class="pop">
                Volver
            </div>
        </a>
    </div>
</body>
@endsection