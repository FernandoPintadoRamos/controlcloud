<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Turnos;
use App\Centro;
use App\Courtesy;
use App\Empresa;

class CentroController extends Controller
{


    public function cambiarCortesia(Request $request){

        $cortesia_existe = false;
        $cortesias = Courtesy::all();

        foreach($cortesias as $cortesia){
            if($cortesia->courtesy==$request->minutos){
                $cortesia_existe = true;
                $c = Courtesy::find($cortesia->id);
            }
        }

        if(!$cortesia_existe){
            $cortesia = Courtesy::create([
                'courtesy' => $request->minutos,
            ]);

            $centro = Centro::find($request->COD_centro);
            $centro->update([
                'cortesia' => $cortesia->id,
            ]);
            $centro->save();
        }else{
            $centro = Centro::find($request->COD_centro);
            $centro->update([
                'cortesia' => $c->id,
            ]);
            $centro->save();
        }

        return back();
    }

    public function checkHorario($array){

        $con=mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'),env('DB_DATABASE'));
        $sql_turnos = "SELECT * from turnos";
        $result_turnos = mysqli_query($con, $sql_turnos);

        while(($turnos = $result_turnos->fetch_array(MYSQLI_NUM))!=null){
            
            $id = $turnos[0];

            unset($turnos[0]);
            unset($turnos[1]);
            unset($turnos[2]);

            $turnos=array_values($turnos);
            // dd($turnos);
            if($turnos == $array){
                return array(true, $id);
            }
        }

        return array(false, null);

    }

    public function cambiarGeo(Request $request){
        $centro = Centro::find($request->id);
        $centro -> update([
            'UBI'   => $request->geo,
            'RAN'   => $request->rango,
        ]);
        $centro->save();
        return back();
    }

    public function importCSV(Request $request){
        $file = fopen( $request->centros, "r");
        $data = fgetcsv($file,null,';');

        //Archivo de error
        $delimiter = ";";
        $file_error = fopen("C:\CSV\CentrosNoAñadidos_".uniqid().".csv", 'w');
        $fields = array(
            'Codigo', 
            'Nombre', 
            'Empresa', 
            'CIF', 
            'Causa'
        );
        fputcsv($file_error, $fields, $delimiter);

        while (($data = fgetcsv($file,null,';'))!=null) {

            if($data[0]!=null||$data[0]!=''){

                // Datos de centro
                $cod_centro = sprintf("%03d", $data[0]);
                $nombre_centro = utf8_encode($data[1]);
                $emp_centro = sprintf("%04d", $data[2]);
                $cif_centro = $data[3];
                $nombre_empresa = $data[4];
                $usuario_ftp = $data[5];

                $empresa = Empresa::where('CIF', '=', $cif_centro)->first();
                if($empresa == null){
                    Empresa::create([
                        'CIF'           => $cif_centro,
                        'usuario_ftp'   => $usuario_ftp,
                        'id_supervisor' => Auth::user()->id
                    ]);
                }

                unset($data[0]);
                unset($data[1]);
                unset($data[2]);
                unset($data[3]);
                unset($data[4]);
                unset($data[5]);

                $data = array_values($data);

                //Comprobamos que no existe el centro ya
                $centro = Centro::where('COD', '=', $cod_centro)
                                ->where('EMP', '=', $emp_centro)
                                ->where('CIF', '=', $cif_centro)
                                ->first();
                
                if($centro != null){
                    $fields = array(
                        $cod_centro, 
                        $nombre_centro, 
                        $emp_centro, 
                        $cif_centro, 
                        'El centro ya existe'
                    );
                    fputcsv($file_error, $fields, $delimiter);
                }else{

                    // Comprobamos si tiene horario valido
                    $valido = false;
                    foreach($data as $d){
                        if($d != null||$d!=""){
                            $valido = true;
                        }
                    }

                    if(!$valido){
                        $fields = array(
                            $cod_centro, 
                            $nombre_centro, 
                            $emp_centro, 
                            $cif_centro, 
                            'Horario no valido'
                        );
                        fputcsv($file_error, $fields, $delimiter);
                    }else{
                        // Comprobamos que el horario no exista ya
                        $cont = 0;
                        foreach($data as $d){
                            if($d == ''){
                                $data[$cont] = null;
                            }else{
                            
                                $prim = substr($d, 0, -2);
                                $seg = substr($d, -2);

                                $data[$cont] = $prim.':'.$seg;
                            }
                            
                            $cont = $cont + 1;
                        }
                        $result = CentroController::checkHorario($data);
                        $id_turno = null;

                        if($result[0]){
                            $id_turno = $result[1];
                            dd($result);
                        }else{

                            $turno = Turnos::create([
                                // Lunes   
                                
                                'LMD' => $data[0],    //Lunes Mañana Desde
                                'LMH' => $data[1],
                                'LTD' => $data[2],    
                                'LTH' => $data[3],
                                'LND' => $data[4],
                                'LNH' => $data[5],
                                'LED' => $data[6],
                                'LEH' => $data[7],
                                'LCD' => $data[8],
                                'LCH' => $data[9],
        
                                // Martes
                                'MMD' => $data[10],    //Martes Mañana Desde
                                'MMH' => $data[11],
                                'MTD' => $data[12],
                                'MTH' => $data[13],
                                'MND' => $data[14],
                                'MNH' => $data[15],
                                'MED' => $data[16],
                                'MEH' => $data[17],
                                'MCD' => $data[18],
                                'MCH' => $data[19],
        
                                // Miercoles
                                'XMD' => $data[20],    //Miercoles Mañana Desde
                                'XMH' => $data[21],
                                'XTD' => $data[22],
                                'XTH' => $data[23],
                                'XND' => $data[24],
                                'XNH' => $data[25],
                                'XED' => $data[26],
                                'XEH' => $data[27],
                                'XCD' => $data[28],
                                'XCH' => $data[29],
        
                                // Jueves
                                'JMD' => $data[30],    //Jueves Mañana Desde
                                'JMH' => $data[31],
                                'JTD' => $data[32],
                                'JTH' => $data[33],
                                'JND' => $data[34],
                                'JNH' => $data[35],
                                'JED' => $data[36],
                                'JEH' => $data[37],
                                'JCD' => $data[38],
                                'JCH' => $data[39],
        
                                // Viernes
                                'VMD' => $data[40],    //Viernes Mañana Desde
                                'VMH' => $data[41],
                                'VTD' => $data[42],
                                'VTH' => $data[43],
                                'VND' => $data[44],
                                'VNH' => $data[45],
                                'VED' => $data[46],
                                'VEH' => $data[47],
                                'VCD' => $data[48],
                                'VCH' => $data[49],
        
                                // Sabado
                                'SMD' => $data[50],
                                'SMH' => $data[51],
                                'STD' => $data[52],
                                'STH' => $data[53],
                                'SND' => $data[54],
                                'SNH' => $data[55],
                                'SED' => $data[56],
                                'SEH' => $data[57],
                                'SCD' => $data[58],
                                'SCH' => $data[59],
                                
                                // Domingo
                                'DMD' => $data[60],
                                'DMH' => $data[61],
                                'DTD' => $data[62],
                                'DTH' => $data[63],
                                'DND' => $data[64],
                                'DNH' => $data[65],
                                'DED' => $data[66],
                                'DEH' => $data[67],
                                'DCD' => $data[68],
                                'DCH' => $data[69],
                            ]);

                            $id_turno = $turno->id;
                        }

                        $centro = Centro::create([
                            'COD' => $cod_centro,
                            'NOM' => $nombre_centro,
                            'EMP' => $emp_centro,
                            'CIF' => $cif_centro,
                            'NOM_EMP' => $nombre_empresa,
                            'horario' => $id_turno,
                        ]);
                    }
                }
            }
        }

        fclose($file);
        fclose($file_error);

        return back();
    }

    public function show(){
        if(Auth::user()->role=='jefe'){
            $centros=Centro::where('EMP', '=', Auth::user()->EMP)->get()->toArray();
        }else{
            $centros=Centro::all();
        }

        $centro_array = $centros[0];
        
        $centro = Centro::find($centro_array["id"]);

        return view('centros.show', compact('centro'));
    }

    public function buscarCentro(Request $request){

        $centro = Centro::find($request->selectEmpleado);
        

        return view('centros.show', compact('centro'));
    }

    public function back(Request $request){
        $centro = Centro::find($request->centro)->toArray();

        if(Auth::user()->role=='jefe'){
            $all_centro=Centro::where('CIF', '=', Auth::user()->CIF)->get()->toArray();
        }else{
            $all_centro=Centro::get()->toArray();
        }

        $indice_centro = array_search($centro, $all_centro);
        $cont = $indice_centro - 1;

        if($cont < 0){

            $centro = Centro::find($centro['id']);

            return view('centros.show', compact('centro'));
        }

        if(Auth::user()->role == 'jefe'){
            $centro = $all_centro[$cont];
            $centro = Centro::find($centro['id']);
            return view('centros.show', compact('centro'));
        }else{
            $cont_emp = 0;
            while($cont >= 0){
                $centro = $all_centro[$cont];
                $all_cifs = Empresa::where('id_supervisor', '=', Auth::user()->id)->get("CIF")->toArray();

                foreach($all_cifs as $cif){
                    $all_cifs[$cont_emp] = $cif["CIF"];
                    $cont_emp++;
                }

                if(in_array($centro["CIF"], $all_cifs)){
                    $centro = Centro::find($centro['id']);
                    return view('centros.show', compact('centro'));
                }
                $cont--;
            }
        }
    }

    public function next(Request $request){
        $centro = Centro::find($request->centro)->toArray();

        if(Auth::user()->role=='jefe'){
            $all_centro=Centro::where('CIF', '=', Auth::user()->CIF)->get()->toArray();
        }else{
            $all_centro=Centro::get()->toArray();
        }

        $indice_centro = array_search($centro, $all_centro);
        $cont = $indice_centro + 1;

        if($cont >= sizeof($all_centro)){

            $centro = Centro::find($centro['id']);

            return view('centros.show', compact('centro'));
        }

        if(Auth::user()->role == 'jefe'){
            $centro = $all_centro[$cont];
            $centro = Centro::find($centro['id']);
            return view('centros.show', compact('centro'));
        }else{
            $cont_emp = 0;
            while($cont <= sizeof($all_centro)){
                $centro = $all_centro[$cont];
                $all_cifs = Empresa::where('id_supervisor', '=', Auth::user()->id)->get("CIF")->toArray();

                foreach($all_cifs as $cif){
                    $all_cifs[$cont_emp] = $cif["CIF"];
                    $cont_emp++;
                }

                if(in_array($centro["CIF"], $all_cifs)){
                    $centro = Centro::find($centro['id']);
                    return view('centros.show', compact('centro'));
                }
                $cont++;
            }
        }
    }

    public function inicio(){
        if(Auth::user()->role=='jefe'){
            $centros = Centro::where('EMP', '=', Auth::user()->EMP)->orderByRaw('EMP DESC')->orderByRaw('COD ASC')->get()
                                            ->toArray();
        }else{
            $centros = Centro::orderByRaw('EMP DESC')->orderByRaw('COD ASC')->get()
                                            ->toArray();
        }

        if(Auth::user()->role=='jefe'){

            $centro = $centros[0];
            $centro = Centro::find($centro['id']);
            return view('centros.show', compact('centro'));
        }else{

            $all_cifs = Empresa::where('id_supervisor', '=', Auth::user()->id)->get("CIF")->toArray();
            $cont = 0;
            foreach($all_cifs as $cif){
                $all_cifs[$cont] = $cif["CIF"];
                $cont++;
            }

            foreach($centros as $centro){
                if(in_array($centro['CIF'], $all_cifs)){
                    
                    $centro = Centro::find($centro['id']);
                    return view('centros.show', compact('centro'));
                }
            }
        }
    }

    public function fin(){
        if(Auth::user()->role=='jefe'){
            $centros = Centro::where('EMP', '=', Auth::user()->EMP)->orderByRaw('EMP DESC')->orderByRaw('COD DESC')->get()
                                            ->toArray();
        }else{
            $centros = Centro::orderByRaw('EMP DESC')->orderByRaw('COD DESC')->get()
                                            ->toArray();
        }

        if(Auth::user()->role=='jefe'){

            $centro = $centros[sizeof($centros)];
            $centro = Centro::find($centro['id']);
            return view('centros.show', compact('centro'));
        }else{

            $all_cifs = Empresa::where('id_supervisor', '=', Auth::user()->id)->get("CIF")->toArray();
            $cont = 0;
            foreach($all_cifs as $cif){
                $all_cifs[$cont] = $cif["CIF"];
                $cont++;
            }

            foreach($centros as $centro){
                if(in_array($centro['CIF'], $all_cifs)){
                    
                    $centro = Centro::find($centro['id']);
                    return view('centros.show', compact('centro'));
                }
            }
        }
    }
}
