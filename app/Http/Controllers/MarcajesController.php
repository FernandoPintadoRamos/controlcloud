<?php

namespace App\Http\Controllers;

use App\Marcajes;
use Illuminate\Http\Request;
use App\Http\Requests\MarcajesRequest;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Courtesy;
use App\Centro;
use App\Turnos;
use App\Empresa;
use App\Http\Controllers\RegistroController;
use Barryvdh\DomPDF\Facade as PDF;

require "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class MarcajesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->store($request);
        return redirect()->route('misMarcajes');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function filtrarPorRangoCentro($d_centro, $h_centro, $all_centros){
        
        $centros_filtrados = array();

        if($d_centro == ""){ $d_centro = $all_centros[0]['NOM']; }
        if($h_centro == ""){ $h_centro = $all_centros[sizeof($all_centros)-1]['NOM']; }

        $entra = 0;
        foreach($all_centros as $centro){
            if($centro['NOM'] == $d_centro){
                $entra = 1;
            }

            if($centro['NOM'] == $h_centro){
                $entra = 0;
                array_push($centros_filtrados, $centro['COD']);
            }

            if($entra == 1){
                array_push($centros_filtrados, $centro['COD']);
            }
        }

        return $centros_filtrados;
    }

    public function filtrarPorRangoEmpleado($d_empleado, $h_empleado, $all_users){

        $users_filtrados = array();
        if($d_empleado == null){ $d_empleado = $all_users[0]['NOM'].'-'.$all_users[0]['AP1'].'-'.$all_users[0]['AP2'].'-'.$all_users[0]['COD']; }
        if($h_empleado == null){ $h_empleado = $all_users[sizeof($all_users)-1]['NOM'].'-'.$all_users[sizeof($all_users)-1]['AP1'].'-'.$all_users[sizeof($all_users)-1]['AP2'].'-'.$all_users[sizeof($all_users)-1]['COD']; }

        $entra = 0;

        foreach($all_users as $user){
            if(
                $user['NOM'].'-'.$user['AP1'].'-'.$user['AP2'].'-'.$user['COD'] == $d_empleado
            ){
                $entra = 1;
            }

            if(
                $user['NOM'].'-'.$user['AP1'].'-'.$user['AP2'].'-'.$user['COD'] == $h_empleado
            ){
                $entra = 0;
                array_push($users_filtrados, $user['id']);
            }

            if($entra == 1){
                array_push($users_filtrados, $user['id']);
            }
        }

        return $users_filtrados;
    }

    public function filtrarPorRangoAño($d_año, $h_año, $marcajes){
        $all_marcajes = array();
        // Comprobamos limites
        if($d_año == null){ $d_año = intval( date("Y") - 5); }
        if($h_año == null){ $h_año = intval(date("Y") + 5); }

        foreach($marcajes as $m){
            //Sacamos el mes del marcaje
            $año_marcaje = intval(explode('-', $m['entrance'])[0]);

            //Comprobamos que se encuentra en el rango
            if($d_año <= $año_marcaje && $año_marcaje <= $h_año){   
                array_push($all_marcajes, $m);
            }
        }

        return $all_marcajes;
    }

    public function filtrarPorRangoMes($d_mes, $h_mes, $marcajes){
        $all_marcajes = array();
        // Comprobamos limites
        if($d_mes == null){ $d_mes = 1; }
        if($h_mes == null){ $h_mes = 12; }

        foreach($marcajes as $m){
            //Sacamos el mes del marcaje
            $mes_marcaje = intval(explode('-', $m['entrance'])[1]);

            //Comprobamos que se encuentra en el rango
            if($d_mes <= $mes_marcaje && $mes_marcaje <= $h_mes){   
                array_push($all_marcajes, $m);
            }
        }

        return $all_marcajes;
    }
    
    public function genCSV(Request $request){
        // Mes
        $desde_mes = $request->desdeMes;
        $hasta_mes = $request->hastaMes;

        //Año
        $desde_año = $request->desdeAño;
        $hasta_año = $request->hastaAño;

        //Datos usuario
        $cif = explode('-', $request->selectCentroDesde)[2];
        $empresa = explode('-', $request->selectCentroDesde)[1];

        //Centro
        $desde_centro = explode('-', $request->selectCentroDesde)[0];
        $hasta_centro = explode('-', $request->selectCentroHasta)[0];

        // Codigo empleado
        $desde_empleado = $request->selectEmpleadoDesde;
        $hasta_empleado = $request->selectEmpleadoHasta;

        // Buscar users con CIF = cif y EMP = empresa
        $all_users = User::where('CIF', '=', $cif)->where('EMP', '=', $empresa)->orderBy('AP1')->orderBy('AP2')->orderBy('NOM')->orderBy('COD')->get()->toArray();
        $all_centros = Centro::where('CIF', '=', $cif)->where('EMP', '=', $empresa)->orderBy('NOM')->get()->toArray();

        // Obtenemos centros dentro del rango    
        $all_centros = MarcajesController::filtrarPorRangoCentro($desde_centro, $hasta_centro, $all_centros);

        // Filtramos usuarios
        $all_users = MarcajesController::filtrarPorRangoEmpleado($desde_empleado, $hasta_empleado, $all_users);
        $nom_emp = User::find($all_users[0])->NOM_EMP;

        // Recogemos todos los marcajes y los filtramos por los datos que quremos
        $all_marcajes = Marcajes::all()->toArray();
        $marcajes_filtrados_usuario  = array();

            // Filtrado por usuario
        foreach($all_users as $users){
            foreach($all_marcajes as $marcajes){
                if($marcajes['id_worker'] == $users){
                    array_push($marcajes_filtrados_usuario, $marcajes);
                }
            }
        }
        $marcaje_filtrado = array();
            // Filtrado por centro
        foreach($marcajes_filtrados_usuario as $marcajes){
            if(in_array($marcajes['CEN'], $all_centros)){
                array_push($marcaje_filtrado, $marcajes);
            }
        }

        if($request->selectTipoInforme == "M"){
            MarcajesController::CSV_tipo2($marcaje_filtrado, $desde_mes, $hasta_mes);
        }

        if($request->selectTipoInforme == "D"){
            MarcajesController::CSV_tipo1($marcaje_filtrado, $desde_mes, $hasta_mes, $nom_emp, $cif);
        }

        return back();
    }

    public function CSV_tipo1($marcajes, $d_mes, $h_mes, $nom_emp, $cif){
        $spread = new Spreadsheet();

        $styleArray = array(
            'font'  => array(
                'bold'  => true,
                'style'  => 'Tahoma',
            ),
        );

        $sheet = $spread->getActiveSheet();

        if($d_mes == null){ $d_mes = 1; }
        if($h_mes == null){ $h_mes = 12; }
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('PhpSpreadsheet logo');
        $drawing->setDescription('PhpSpreadsheet logo');
        $drawing->setPath(__DIR__ . '/logoHK.png');
        $drawing->setHeight(50);
        $drawing->setCoordinates('C1');
        $drawing->setOffsetX(10);
        $drawing->setWorksheet($sheet);

        $fila = 5;

        $sheet->mergeCells("A1:Z3");
        $sheet->setCellValueByColumnAndRow(1, $fila, "REGISTRO DIARIO DE HORAS TRABAJADAS DESDE MES: $d_mes HASTA MES: $h_mes");
        $sheet->getStyle('A'.$fila.':J'.$fila)->applyFromArray($styleArray);

        $fila++; $fila++;

        $sheet->mergeCells("A3:Z3");
        $sheet->freezePane('Z9');
        $sheet->setCellValueByColumnAndRow(1, $fila, "EMPRESA: $nom_emp");
        $sheet->getStyle('A'.$fila.':J'.$fila)->applyFromArray($styleArray);

        $fila++;

        $sheet->mergeCells("A4:Z4");
        $sheet->setCellValueByColumnAndRow(1, $fila, "CIF: $cif");
        $sheet->getStyle('A'.$fila.':J'.$fila)->applyFromArray($styleArray);

        $fila++;

        $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
 
        $last_id = 0;
        $last_dia = 0;

        foreach($marcajes as $m){
            //Recogemos user de los marcajes
            $user = User::find($m['id_worker']);

            //Recogemos el dia del marcaje
            $nuevo_dia = intval(explode('-', $m['entrance'])[2]);

            if($user->id != $last_id || $nuevo_dia != $last_dia){
                
                $cont = 3;
                if($user->id != $last_id){
                    $fila++;
                    $suma_horas = 0;
                    // Cabecera nuevo empleado
                    $fila++;
                    $sheet->setCellValueByColumnAndRow(1, $fila, "TRABAJADOR: $user->COD $user->AP1 $user->AP2, $user->NOM");
                    $sheet->getStyle('A'.$fila.':J'.$fila)->applyFromArray($styleArray);
                    $fila++; 
                    $sheet->setCellValueByColumnAndRow(1, $fila, "DNI: $user->DNI");
                    $sheet->getStyle('A'.$fila.':J'.$fila)->applyFromArray($styleArray);
                    $fila++;
                    
                    $sheet->setCellValueByColumnAndRow(1, $fila, "Fecha");
                    $sheet->setCellValueByColumnAndRow(2, $fila, "Dia");
                    $sheet->setCellValueByColumnAndRow(3, $fila, "Desde-Hasta");
                    $sheet->setCellValueByColumnAndRow(4, $fila, "Desde-Hasta");
                    $sheet->setCellValueByColumnAndRow(5, $fila, "Desde-Hasta");
                    $sheet->setCellValueByColumnAndRow(6, $fila, "Desde-Hasta");
                    $sheet->setCellValueByColumnAndRow(7, $fila, "Complement.");
                    $sheet->setCellValueByColumnAndRow(8, $fila, "H.Comp");
                    $sheet->setCellValueByColumnAndRow(9, $fila, "H.CTP");
                    $sheet->setCellValueByColumnAndRow(10, $fila, "T.Horas");

                    $sheet->getColumnDimension('C')->setWidth(17);
                    $sheet->getColumnDimension('D')->setWidth(17);
                    $sheet->getColumnDimension('E')->setWidth(17);
                    $sheet->getColumnDimension('F')->setWidth(17);
                    $sheet->getColumnDimension('G')->setWidth(17);
                    
                    $sheet->getStyle('A'.$fila.':J'.$fila)->getAlignment()->setHorizontal('center');
                    $sheet->getStyle('A'.$fila.':J'.$fila)->applyFromArray($styleArray);
                    
                    $primera_fila = $fila;
                }
                $suma_horas = 0;

                $fila++;
                $sheet->getStyle('A'.$fila.':J'.$fila)->getAlignment()->setHorizontal('center');
                
                $dia = $dias[(date('N', strtotime($m['entrance']))) - 1];

                $sheet->setCellValueByColumnAndRow(1, $fila, $m['entrance']);
                $sheet->setCellValueByColumnAndRow(2, $fila, $dia);
                $sheet->setCellValueByColumnAndRow($cont, $fila, $m['check_in_time'].'-'.$m['departure_time']);

                $total_horas_comp = date("H:i:s", strtotime("00:00:00") + strtotime($m['departure_time']) - strtotime($m['check_in_time']));
                $horas = floatval(explode(':', $total_horas_comp)[0]);
                $minutos = floatval(explode(':', $total_horas_comp)[1])/60;

                $suma_horas = $suma_horas + $horas + $minutos;


            }else{
                $sheet->getStyle('A'.$fila.':J'.$fila)->getAlignment()->setHorizontal('center');
                
                $dia = $dias[(date('N', strtotime($m['entrance']))) - 1];

                $sheet->setCellValueByColumnAndRow(1, $fila, $m['entrance']);
                $sheet->setCellValueByColumnAndRow(2, $fila, $dia);

                $cont++;
                if($cont<=7){
                    if($m['departure_time'] != null){
                        $sheet->setCellValueByColumnAndRow($cont, $fila, $m['check_in_time'].'-'.$m['departure_time']);
                    }   
                }

                if($cont<=6){
                    if($m['departure_time'] != null){
                        
                        $total_horas_comp = date("H:i:s", strtotime("00:00:00") + strtotime($m['departure_time']) - strtotime($m['check_in_time']));
                        $horas = floatval(explode(':', $total_horas_comp)[0]);
                        $minutos = floatval(explode(':', $total_horas_comp)[1])/60;

                        $suma_horas = $suma_horas + $horas + $minutos;
                    }
                }

            }

            $comprobar_nulo = $sheet->getCell('G'.$fila)->getValue();
            if($comprobar_nulo != null && $comprobar_nulo != "TOTALES"){
                $horas_comp = explode('-', $sheet->getCell('G'.$fila)->getValue());
                $horas_comp_1 = $horas_comp[0];
                $horas_comp_2 = $horas_comp[1];

                $total_horas_comp = date("H:i:s", strtotime("00:00:00") + strtotime($horas_comp_2) - strtotime($horas_comp_1));
                $horas = floatval(explode(':', $total_horas_comp)[0]);
                $minutos = floatval(explode(':', $total_horas_comp)[1])/60;

                $suma = $horas + $minutos;
                
                $sheet->setCellValueByColumnAndRow(8, $fila, $suma);
            }

            foreach(range('C', 'G') as $l){
                if($sheet->getCell($l.$fila)->getValue() == null){
                    $sheet->setCellValue( $l.$fila, '-');
                }
            }

            foreach(range('H', 'J') as $l){
                if($sheet->getCell($l.$fila)->getValue() == null){
                    $sheet->setCellValue( $l.$fila, '0');
                }
            }
            $sheet->setCellValueByColumnAndRow(10, $fila, $suma_horas);

            $sheet->setCellValue( 'G'.($fila + 1), 'TOTALES');
            $sheet->getStyle('G'.($fila+1))->applyFromArray($styleArray);
            $sheet->getStyle('H'.($fila+1).':J'.($fila+1))->getAlignment()->setHorizontal('center');
            $sheet->getStyle('G'.($fila+1))->getAlignment()->setHorizontal('right');

            $sheet->setCellValue( 'H'.($fila + 1), "=SUM(H$primera_fila:H$fila)");
            $sheet->setCellValue( 'I'.($fila + 1), "=SUM(I$primera_fila:I$fila)");
            $sheet->setCellValue( 'J'.($fila + 1), "=SUM(J$primera_fila:J$fila)");

            $last_dia = $nuevo_dia;
            $last_id = $user->id;

        }



        $writer = new Xlsx($spread);
        $fileName="Marcajes Diario.xlsx";
        # Le pasamos la ruta de guardado
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
        exit();
    }

    public function CSV_tipo2($marcajes, $d_mes, $h_mes){
        $spread = new Spreadsheet();
        

        $styleArray = array(
            'font'  => array(
                'bold'  => true,
                'style'  => 'Tahoma',
            ),
            'borders' => array(
                'top' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );
        $styleArray_bottom = array(
            'font'  => array(
                'bold'  => true,
                'style'  => 'Tahoma',
            ),
            'borders' => array(
                'bottom' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );
        
        if($d_mes == null){ $d_mes = 1; }
        if($h_mes == null){ $h_mes = 12; }
        
        $fila = 5;
        $sheet = $spread->getActiveSheet();
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('PhpSpreadsheet logo');
        $drawing->setDescription('PhpSpreadsheet logo');
        $drawing->setPath(__DIR__ . '/logoHK.png');
        $drawing->setHeight(50);
        $drawing->setCoordinates('C2');
        $drawing->setOffsetX(10);
        $drawing->setWorksheet($sheet);
        $sheet->mergeCells("A1:F1");
        $sheet->setCellValueByColumnAndRow(1, $fila, "LISTADO DE HORAS TRABAJADAS DEL PERSONAL DESDE MES: $d_mes HASTA MES: $h_mes");
        $fila++; $fila++;
        $sheet->setAutoFilter('A'.$fila.':AI'.$fila);
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(5);
        
        $sheet->getColumnDimension('AI')->setWidth(20);

        $sheet->getStyle('A'.$fila)->applyFromArray($styleArray);
        $sheet->getStyle('B'.$fila)->applyFromArray($styleArray);
        $sheet->getStyle('C'.$fila)->applyFromArray($styleArray);
        
        foreach(range('D', 'Z') as $l){
            $sheet->getColumnDimension($l)->setWidth(8);
            $sheet->getStyle($l.$fila)->applyFromArray($styleArray);
        }

        foreach(range('A', 'H') as $l){
            $sheet->getColumnDimension('A'.$l)->setWidth(8);
            $sheet->getStyle('A'.$l.$fila)->applyFromArray($styleArray);
        }

        
        $sheet->getStyle('AI'.$fila)->applyFromArray($styleArray);
        $sheet->getStyle('A'.$fila.':AH'.$fila)->getAlignment()->setHorizontal('center');

        // $sheet->freezePane('AH1'); Congela verticalmente desde AH1 hasta AHX
        $sheet->freezePane('A'.($fila + 1)); // Congela hasta la columan A sin inclurla y fila 2 sin incluirla
        $sheet->setTitle("Hoja 1");
        $sheet->setCellValueByColumnAndRow(1, $fila, "Código");
        $sheet->setCellValueByColumnAndRow(2, $fila, "Apellidos y Nombre");
        $sheet->setCellValueByColumnAndRow(3, $fila, "Mes");

        $cont = 1;
        while($cont<=32){
            $sheet->setCellValueByColumnAndRow($cont+3, $fila, strval($cont));
            $cont++;
        }
        $sheet->setCellValueByColumnAndRow($cont+2, $fila, 'Horas Mensuales');
        
        $last_mes = 0;
        $last_id = 0;
        $last_dia = 0;
        $suma_horas = 0;
        $last_num_horas = 0;

        foreach($marcajes as $m){
            $cont = 1;
            
            // Recogemos el productor del marcaje
            $user = User::find($m['id_worker']);
            $nuevo_mes = intval(explode('-', $m['entrance'])[1]);
            $nuevo_dia = intval(explode('-', $m['entrance'])[2]) + 3;

            if($user->id != $last_id || $nuevo_mes != $last_mes){
                $suma_horas = 0;
                $fila++;
                while($cont<=31){
                    $sheet->setCellValueByColumnAndRow($cont+3, $fila, 0);
                    $cont++;
                }

                $sheet->setCellValueByColumnAndRow(1, $fila, $user->COD);
                $sheet->setCellValueByColumnAndRow(2, $fila, $user->AP1.' '.$user->AP2.', '.$user->NOM);
                $sheet->setCellValueByColumnAndRow(3, $fila, $nuevo_mes);

                $numero_horas = $m['totalHoras'];
                $suma_horas = $suma_horas + $numero_horas;
                $sheet->setCellValueByColumnAndRow($nuevo_dia, $fila, $numero_horas);

            }else{
                if($nuevo_dia == $last_dia){
                    $numero_horas = $numero_horas +$m['totalHoras'];
                    $suma_horas = $suma_horas + $m['totalHoras'];
                    $sheet->setCellValueByColumnAndRow($nuevo_dia, $fila, $numero_horas);

                }else{ 
                    $numero_horas = $m['totalHoras'];
                    $sheet->setCellValueByColumnAndRow($nuevo_dia, $fila, $numero_horas);
                    $suma_horas = $suma_horas + $numero_horas;
                }
            }

            $sheet->setCellValueByColumnAndRow(35, $fila, $suma_horas);

            $last_mes = $nuevo_mes;
            $last_id = $user->id;
            $last_dia = $nuevo_dia;
            $last_num_horas = $m['totalHoras'];
        }  

        $fila++;
        $sheet->setCellValueByColumnAndRow(2, $fila, 'Total Horas');

        foreach(range('D', 'Z') as $l){
            $sheet->setCellValue( $l.$fila, '=SUM('.$l.'8'.':'.$l.($fila-1).')');
        }

        foreach(range('A', 'H') as $l){
            $sheet->setCellValue( 'A'.$l.$fila, '=SUM(A'.$l.'8'.':A'.$l.($fila-1).')');
        }
        $fila++;
        foreach(range('A', 'Z') as $l){
            $sheet->getStyle($l.$fila)->applyFromArray($styleArray_bottom);
        }

        foreach(range('A', 'H') as $l){
            $sheet->getStyle('A'.$l.$fila)->applyFromArray($styleArray_bottom);
        }
        $sheet->getStyle('AI'.$fila)->applyFromArray($styleArray_bottom);


        $writer = new Xlsx($spread);
        $fileName="Marcajes_Mensual.xlsx";
        # Le pasamos la ruta de guardado
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
        exit();
    }

    public function store(Request $request)
    {   
        $centro = 0;
        if($request->get('centros')=="1"){
            $centro = Auth::user()->CEN;
        }

        if($request->get('centros')=="2"){
            $centro = Auth::user()->CEN_02;
        }

        if($request->get('centros')=="3"){
            $centro = Auth::user()->CEN_03;
        }

        if($request->get('centros')=="4"){
            $centro = Auth::user()->CEN_04;
        }

        if($request->get('centros')=="5"){
            $centro = Auth::user()->CEN_05;
        }

        $cen = Centro::where('COD', '=', $centro)
                    ->where('EMP', '=', Auth::user()->EMP)
                    ->where('CIF', '=', Auth::user()->CIF)
                    ->first();

        if($cen->UBI!=null){
            if($request->ubicacion == null){
                return back()->with('message', ['danger', __("No ha dado permiso para ver su ubicación")]);
            }
            $coords = explode(",", $request->ubicacion);
        
            $lat = (doubleval($coords[0]))*1000;
            $lon = (doubleval($coords[1]))*1000;

            $coords_cen = explode(",", $cen->UBI);
            $lat_cen = doubleval($coords_cen[0])*1000;
            $lon_cen = doubleval($coords_cen[1])*1000;
            
            if($lat <= $lat_cen-$cen->RAN || $lat >= $lat_cen+$cen->RAN){
                
                if($lon <= $lon_cen-$cen->RAN || $lon >= $lon_cen+$cen->RAN){
                    return back()->with('message', ['danger', __("No se encuentra en el centro")]);
                }
            }
        }
        
       

        

        $usuario = Auth::id();  // Recoge el ID del usuario que tenga la sesión iniciada
        $marcajes = Marcajes::orderBy('created_at', 'asc')->where('id_worker',$usuario)->get()->toArray();
        $last_marcaje = end($marcajes); // recoge el último registro

        $actual_time = date('H:i:s');
        $actual_date = date('Y-m-d');

        

        $user = Auth::user(); // Coge el registro entero
        if(sizeof($marcajes)){
            $marcaje = Marcajes::find($last_marcaje['id']);

            if($last_marcaje['entrance']!=$actual_date){
                $marcaje = Marcajes::create([
                    'entrance' => $actual_date,
                    'check_in_time' => $actual_time,
                    'nature_of_work' => null,
                    'exit' => null,
                    'departure_time' => null,
                    'entrance_note' => $request->nota,
                    'id_worker' => $user->id,
                    'CEN'       => $centro,
                    'EMP'       => $user->EMP
                ]);
                if($marcaje)
                    return redirect()->route('misMarcajes')->with('message', ['success', __("Entrada marcada con éxito")]);
                else
                    return back()->with('message', ['danger', __("Error al marcar entrada")]);

            }

            if($last_marcaje['exit']==null){    // Y el dia es igual
                // Calculamos horas totales
                $fecha_1 = $marcaje->check_in_time;
                $fecha_2 = $actual_time;

                $hora_1 = intval(substr($fecha_1, 0, 2));
                $hora_2 = intval(substr($fecha_2, 0, 2));

                $minutos_1 = intval(substr($fecha_1, 3, 2));
                $minutos_2 = intval(substr($fecha_2, 3, 2));

                $hora_1 = round($hora_1 + ($minutos_1 / 60), 2);
                $hora_2 = round($hora_2 + ($minutos_2 / 60), 2);

                $marcaje->update([
                    'entrance' => $marcaje->entrance,
                    'check_in_time' => $marcaje->check_in_time,
                    'nature_of_work' => null,
                    'exit' => $actual_date,
                    'departure_time' => $actual_time,
                    'exit_note' => $request->nota,
                    'totalHoras' => ($hora_2-$hora_1)
                ]);
                $marcaje->save();

                RegistroController::store($marcaje, $usuario);

                if($marcaje)
                    return redirect()->route('misMarcajes')->with('message', ['success', __("Salida marcada con éxito")]);
                else
                    return back()->with('message', ['danger', __("Error al marcar salida")]);
            }else{
                $marcaje = Marcajes::create([
                    'entrance' => $actual_date,
                    'check_in_time' => $actual_time,
                    'nature_of_work' => null,
                    'exit' => null,
                    'departure_time' => null,
                    'entrance_note' => $request->nota,
                    'id_worker' => $user->id,
                    'CEN'       => $centro,
                    'EMP'       => $user->EMP,
                    'CIF'       => $user->CIF,
                ]);
                if($marcaje)
                    return redirect()->route('misMarcajes')->with('message', ['success', __("Entrada creada con éxito")]);
                else
                    return back()->with('message', ['danger', __("Error al marcar salida")]);    
            }
        }else{
            $marcaje = Marcajes::create([
                'entrance' => $actual_date,
                'check_in_time' => $actual_time,
                'nature_of_work' => null,
                'exit' => null,
                'departure_time' => null,
                'entrance_note' => $request->nota,
                'id_worker' => $user->id,
                'CEN'       => $centro,
                'EMP'       => $user->EMP,
                'CIF'       => $user->CIF,
            ]);
            if($marcaje)
                return redirect()->route('misMarcajes')->with('message', ['success', __("Entrada marcada con éxito")]);
            else
                return back()->with('message', ['danger', __("Error al marcar entrada")]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Marcajes  $marcajes
     * @return \Illuminate\Http\Response
     */
    public function show(Marcajes $marcajes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Marcajes  $marcajes
     * @return \Illuminate\Http\Response
     */
    public function edit(Marcajes $marcajes)
    {
        //
    }


    public static function checkCentro($id_user, $centro)
    {   
        $usuario = User::find($id_user);
        $match = false;

        if($usuario->CEN == $centro){
            $match = true;
        }

        if($usuario->CEN_02 == $centro){
            $match = true;
        }

        if($usuario->CEN_03 == $centro){
            $match = true;
        }

        if($usuario->CEN_04 == $centro){
            $match = true;
        }

        if($usuario->CEN_05 == $centro){
            $match = true;
        }

        return $match;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Marcajes  $marcajes
     * @return \Illuminate\Http\Response
     */
    //public function update(Request $request, Marcajes $marcajes)
    public function update(Request $request, $id)
    {
        $marcaje = Marcajes::find($id);

        if(MarcajesController::checkCentro($marcaje->id_worker, $request->CEN)){
            //  Modificando marcaje
            $marcaje->update([
                'entrance' => $request->entrance,
                'check_in_time' => $request->check_in_time,
                'entrance_note' => $request->entrance_note,
                'nature_of_work' => $request->nature_of_work,
                'exit' => $request->exit,
                'departure_time' => $request->departure_time,
                'exit_note' => $request->exit_note,
                'CEN'       => $request->CEN,
            ]);
            $marcaje->save();

            return back()->with('message', ['success', __("Marcaje modificado con éxito")]);
        }

        $usuario = User::find($marcaje->id_worker);
        $cen1 = '';
        $cen2 = '';
        $cen3 = '';
        $cen4 = '';
        $cen5 = '';

        if($usuario->CEN != Null){
            $cen1 = $usuario->CEN;
        }

        if($usuario->CEN_02 != Null){
            $cen2 = $usuario->CEN_02;        
        }

        if($usuario->CEN_03 != Null){
            $cen3 = $usuario->CEN_03;        
        }

        if($usuario->CEN_04 != Null){
            $cen4 = $usuario->CEN_04;        
        }

        if($usuario->CEN_05 != Null){
            $cen5 = $usuario->CEN_05;        
        }

        return back()->with('message', ['danger', __("El empleado no pertenece a ese centro.
                                        Sus centros son: $cen1 $cen2 $cen3 $cen4 $cen5")]);
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Marcajes  $marcajes
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marcaje = Marcajes::find($id);
        $marcaje->delete();
        
        if($marcaje)
            return back()->with('message', ['success', __("Marcaje suprimido con éxito")]);
        else
            return back()->with('message', ['danger', __("Error al borrar el marcaje")]);
    }
    /*
    */
    public function misMarcajes()
    {
        $courtesy = Courtesy::find('1');    //  Necesario para que calcule en función de los minutos de cortesía que seleccione el supervisor
        $usuario = Auth::id();              //  Esto sirve para asignar marcajes a un determinado usuario
        $use = User::find($usuario);       //  Me devolverá el nombre del usuario al que se le atribuye el marcaje
        $users = User::all(); 
        $turno = null;  

        //Obtengo el horario del centro
        $marcajes = Marcajes::orderBy('created_at', 'asc')->where('id_worker',$usuario)->get()->toArray();
        if($marcajes){
            $last_marcaje = end($marcajes); // recoge el último registro
            $centro = Centro::where('COD', '=', $last_marcaje['CEN'])->where('EMP', '=', $last_marcaje['EMP'])->first();
            if($use->horario!=null){
                $turno = Turnos::find($use->horario);
                $day = date("l");
            }else{
                $turno = Turnos::find($centro->horario);
                $day = date("l");
            }

            if($centro->cortesia==null){
                $minutosCortesia=$courtesy['courtesy'];
            }else{
                //Obtenemos la cortesia del centro
                $cortesia_centro = Courtesy::find($centro->cortesia);
                $minutosCortesia = $cortesia_centro->courtesy;
            }
            $hora_actual = strtotime(date('h:i'));
            
            if(Auth::user()->role=='supervisor'){
                $horaEntrada="00:00";
                $horaSalida="00:00";
                $turno = null;
            }else{
                switch ($day) {
                    case "Sunday":
                        if($hora_actual > strtotime($turno->DMH)){
                            $horaEntrada=$turno->DMD;
                            $horaSalida=$turno->DMH;
                            $turno = "Domingo Mañana";
                        }elseif($hora_actual > strtotime($turno->DTH)){
                            $horaEntrada=$turno->DTD;
                            $horaSalida=$turno->DTH;
                            $turno = "Domingo Tarde";
                        }elseif($hora_actual > strtotime($turno->DNH)){
                            $horaEntrada=$turno->DND;
                            $horaSalida=$turno->DNH;
                            $turno = "Domingo Noche";
                        }else{
                            return back()->with('message', ['danger', __("No esta dentro de su horario")]); 
                        }
                    break;
                    case "Monday":
                        if($hora_actual > strtotime($turno->LMH)){
                            $horaEntrada=$turno->LMD;
                            $horaSalida=$turno->LMH;
                            $turno = "Lunes Mañana";
                        }elseif($hora_actual > strtotime($turno->LTH)){
                            $horaEntrada=$turno->LTD;
                            $horaSalida=$turno->LTH;
                            $turno = "Lunes Tarde";
                        }elseif($hora_actual > strtotime($turno->LNH)){
                            $horaEntrada=$turno->LND;
                            $horaSalida=$turno->LNH;
                            $turno = "Lunes Noche";
                        }else{
                            return back()->with('message', ['danger', __("No esta dentro de su horario")]); 
                        }
                    break;
                    case "Tuesday":
                        if(in_array($hora_actual, range(strtotime($turno->MMD), strtotime($turno->MMH)))){
                            $horaEntrada=$turno->MMD;
                            $horaSalida=$turno->MMH;
                            $turno = "Martes Mañana";
                        }elseif(in_array($hora_actual, range(strtotime($turno->MTD), strtotime($turno->MTH)))){
                            $horaEntrada=$turno->MTD;
                            $horaSalida=$turno->MTH;
                            $turno = "Martes Tarde";
                        }else{
                            $horaEntrada=$turno->MTD;
                            $horaSalida=$turno->MTH;
                            $turno = null;  
                        }
                    break;
                    case "Wednesday":
                        if($hora_actual > strtotime($turno->XMH)){
                            $horaEntrada=$turno->XMD;
                            $horaSalida=$turno->XMH;
                            $turno = "Miercoles Mañana";
                        }elseif($hora_actual > strtotime($turno->XTH)){
                            $horaEntrada=$turno->XTD;
                            $horaSalida=$turno->XTH;
                            $turno = "Miercoles Tarde";
                        }elseif($hora_actual > strtotime($turno->XNH)){
                            $horaEntrada=$turno->XND;
                            $horaSalida=$turno->XNH;
                            $turno = "Miercoles Noche";
                        }else{
                            return back()->with('message', ['danger', __("No esta dentro de su horario")]); 
                        }
                    break;
                    case "Thursday":
                        if($hora_actual > strtotime($turno->JMH)){
                            $horaEntrada=$turno->JMD;
                            $horaSalida=$turno->JMH;
                            $turno = "Jueves Mañana";
                        }elseif($hora_actual > strtotime($turno->JTH)){
                            $horaEntrada=$turno->JTD;
                            $horaSalida=$turno->JTH;
                            $turno = "Jueves Tarde";
                        }elseif($hora_actual > strtotime($turno->JNH)){
                            $horaEntrada=$turno->JND;
                            $horaSalida=$turno->JNH;
                            $turno = "Jueves Noche";
                        }else{
                            return back()->with('message', ['danger', __("No esta dentro de su horario")]); 
                        }
                    break;
                    case "Friday":
                        if($hora_actual > strtotime($turno->VMH)){
                            $horaEntrada=$turno->VMD;
                            $horaSalida=$turno->VMH;
                            $turno = "Viernes Mañana";
                        }elseif($hora_actual > strtotime($turno->VTH)){
                            $horaEntrada=$turno->VTD;
                            $horaSalida=$turno->VTH;
                            $turno = "Viernes Tarde";
                        }elseif($hora_actual > strtotime($turno->VNH)){
                            $horaEntrada=$turno->VND;
                            $horaSalida=$turno->VNH;
                            $turno = "Viernes Noche";
                        }else{
                            return back()->with('message', ['danger', __("No esta dentro de su horario")]); 
                        }
                    break;
                    case "Saturday":
                        if($hora_actual > strtotime($turno->SMH)){
                            $horaEntrada=$turno->SMD;
                            $horaSalida=$turno->SMH;
                            $turno = "Sábado Mañana";
                        }elseif($hora_actual > strtotime($turno->STH)){
                            $horaEntrada=$turno->STD;
                            $horaSalida=$turno->STH;
                            $turno = "Sábado Tarde";
                        }elseif($hora_actual > strtotime($turno->SNH)){
                            $horaEntrada=$turno->SND;
                            $horaSalida=$turno->SNH;
                            $turno = "Sábado Noche";
                        }else{
                            return back()->with('message', ['danger', __("No esta dentro de su horario")]);
                        }
                    break; 
                }
            }
            
        }else{
            $horaEntrada=$use->timetable_entrance;
            $horaSalida=$use->timetable_exit;
            $minutosCortesia=$courtesy['courtesy'];
        }
        
         
        $segundos_horaEntrada=strtotime($horaEntrada);
        $segundos_horaSalida=strtotime($horaSalida);
         
        $segundos_minutosCortesia=$minutosCortesia*60;
         
        $courtesy_entrance=date("H:i",$segundos_horaEntrada+$segundos_minutosCortesia);
        $courtesy_exit=date("H:i",$segundos_horaSalida+$segundos_minutosCortesia);
        //  Fin cálculos minutos cortesía
        if($use->role == 'empleado'){
            $marcajes = Marcajes::orderBy('created_at', 'asc')->where('id_worker',$usuario)->get()->toArray();
        }elseif($use->role == 'jefe'){
            $marcajes = Marcajes::orderBy('created_at', 'asc')->where('CIF','=',$use->CIF)->get()->toArray();
        } else {
            $marcajes_todos = Marcajes::orderBy('created_at', 'asc')->get()->toArray();
            $all_cifs = Empresa::where('id_supervisor', '=', Auth::user()->id)->get('CIF')->toArray();
            $cont_emp = 0;
            foreach($all_cifs as $cif){
                $all_cifs[$cont_emp] = $cif['CIF'];
                $cont_emp++;
            }

            $marcajes = array();
            foreach($marcajes_todos as $m){
                if(in_array($m['CIF'], $all_cifs)){
                    array_push($marcajes, $m);  
                }
            }
        }
        $marcajes=Array_chunk($marcajes,3,true);
        
        return view('marcajes/misMarcajes', compact('marcajes','users','use','courtesy_entrance', 'courtesy_exit', 'turno'));
        
    }

    public function pdf(Request $req)
    {
        if(isset($req->fecha_entrada) && isset($req->fecha_salida)){

            $marcajes=Marcajes::orderBy('created_at','DESC')->whereBetween('entrance',[$req->fecha_entrada,$req->fecha_salida])->with('nameUser')->get();
            
            $nombre='Registro de Jornada entre '. $req->fecha_entrada .' y '. $req->fecha_salida .'.pdf';
            $pdf = PDF::loadView('marcajes.pdf', compact('marcajes'));

            return $pdf->download($nombre);
        }

    }
}
