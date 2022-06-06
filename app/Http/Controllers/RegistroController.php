<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Registro;
use App\User;
use App\Centro;
use App\Empresa;
use App\Turnos;
class RegistroController extends Controller
{
    public function show(){
        if(Auth::user()->role=='jefe'){
            $registros=Registro::where('CIF', '=', Auth::user()->CIF)->get();
        }else{
            
            $registros_todos=Registro::all();
            $registros = array();
            $all_cifs = Empresa::where('id_supervisor', Auth::user()->id)->get()->toArray();
            $cont_emp = 0;
            
            foreach($all_cifs as $cif){
                $all_cifs[$cont_emp] = $cif['CIF'];
                $cont_emp++;
            }
            foreach($registros_todos as $r){
                if(in_array($r['CIF'], $all_cifs)){
                    array_push($registros, $r);
                }
            }
        }
       

        return view('marcajes/registroHoras', compact('registros'));
    }

    // Cambiar cuando haya datos de verdad //
    public static function horasPrevistas($user, $centro){
        $turno = $centro->horario;
        if($user->CEN==$centro->COD){
            if($user->horario1!=null){
                $turno = $user->horario1;
            }
        }

        if($user->CEN_02==$centro->COD){
            if($user->horario2!=null){
                $turno = $user->horario2;
            }
        }

        if($user->CEN_03==$centro->COD){
            if($user->horario3!=null){
                $turno = $user->horario3;
            }
        }

        if($user->CEN_04==$centro->COD){
            if($user->horario4!=null){
                $turno = $user->horario4;
            }
        }

        if($user->CEN_05==$centro->COD){
            if($user->horario5!=null){
                $turno = $user->horario5;
            }
        }

        $horario = Turnos::find($turno);

        // Retocar a partir de aqui
        $day = date("l");
        switch ($day) {
            case "Sunday":
                return RegistroController::sumarHorasDia('D', $horario);
            break;
            case "Monday":
                return RegistroController::sumarHorasDia('L', $horario);
            break;
            case "Tuesday":      
                return RegistroController::sumarHorasDia('M', $horario);  
            break;
            case "Wednesday":
                return RegistroController::sumarHorasDia('X', $horario);
            break;
            case "Thursday":
                return RegistroController::sumarHorasDia('J', $horario);         
            break;
            case "Friday":
                return RegistroController::sumarHorasDia('V', $horario);
            break;
            case "Saturday":
                return RegistroController::sumarHorasDia('S', $horario);
            break;
        }
        
    }

    public static function sumarHorasDia($dia, $turno){
        $array_turno = array('M', 'T', 'N');
        $suma_horas = 0.0;
        $horario = $turno->toArray();

        foreach($array_turno as $turno){
            $total_horas_turno = date("H:i:s", strtotime("00:00:00") + strtotime($horario[strval($dia.$turno.'H')]) - strtotime($horario[$dia.$turno.'D']));
            $horas = floatval(explode(':', $total_horas_turno)[0]);
            $minutos = floatval(explode(':', $total_horas_turno)[1])/60;
            $suma_horas = $suma_horas + $horas + $minutos;
        }

        return $suma_horas;
    }

    public static function store($marcaje, $usuario){
        
        // Sacamos el usuario y el centro en el que ha fichado
        $user = User::find($usuario);
        $empresa = sprintf("%04d", $marcaje->EMP);
        $centro = Centro::where('COD', '=', $marcaje->CEN)->where('EMP', '=', $empresa)->first(); 

        //Obtenemos hotas previstas ,horas registradas y el dia
        $horas_registradas = intval($marcaje->departure_time) - intval($marcaje->check_in_time);
        $horas_previstas = intval(RegistroController::horasPrevistas($user, $centro));
        $fecha_registro = date('d/m/Y');

        //Obtenemos el ultimo registro si hay alguno
        $ultimo_registro = Registro::where('id_worker', '=', $usuario)->latest()->first();

        //Si no existe ningun registro para ese trabajador
        if($ultimo_registro == null){

            // Comprobamos si faltan horas por echar
            if($horas_registradas < $horas_previstas){
                $bolsa_horas = $horas_previstas - $horas_registradas;
            }else{
                $bolsa_horas = 0;
            }

            Registro::create([
                'horas_previstas' => $horas_previstas,
                'horas_registradas' => $horas_registradas,
                'bolsa_horas' => $bolsa_horas,
                'fecha_registro' => $fecha_registro,
                'horas_compensadas' => 0,
                'id_worker' => $usuario,
                'CIF' => $user->CIF,
            ]);
        }else{  // Si ya existe un registro comprobamos si es del mismo dia

            // Si es el mismo dia actualizamos el registro
            if($ultimo_registro->fecha_registro==$fecha_registro){

                $last_bolsa_horas = $ultimo_registro->bolsa_horas;
                $new_bolsa_horas = $last_bolsa_horas - $horas_registradas;
                $horas_compensadas = $last_bolsa_horas - $new_bolsa_horas;

                if($new_bolsa_horas < 0){
                    $new_bolsa_horas = 0;
                }

                if($horas_compensadas > $last_bolsa_horas){
                    $horas_compensadas = $last_bolsa_horas;
                }

                $ultimo_registro -> update([
                    'horas_registradas' => $ultimo_registro->horas_registradas + $horas_registradas,
                    'bolsa_horas' => $new_bolsa_horas,
                    'horas_compensadas' => $ultimo_registro->horas_compensadas + $horas_compensadas,
                    'CIF' => $user->CIF,
                ]);
            }else{ // Si no creamos un nuevo registro

                // Calculamos la nueva bolsa de horas
                $bolsa_horas = $ultimo_registro->bolsa_horas - ($horas_registradas - $horas_previstas);
                if($bolsa_horas < 0){
                    $bolsa_horas = 0;
                }

                // Horas compensadas
                $horas_compensadas = $ultimo_registro->bolsa_horas - $bolsa_horas ;
                if($horas_compensadas < 0){
                    $horas_compensadas = $ultimo_registro->bolsa_horas;
                }

                Registro::create([
                    'horas_previstas' => $horas_previstas,
                    'horas_registradas' => $horas_registradas,
                    'bolsa_horas' => $bolsa_horas,
                    'fecha_registro' => $fecha_registro,
                    'horas_compensadas' => $horas_compensadas,
                    'id_worker' => $usuario,
                    'CIF' => $user->CIF,
                ]);
            }
        }

        return back();
    }
}
