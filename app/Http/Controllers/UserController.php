<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Centro;
use App\Turnos;
use App\Empresa;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Validator;
use App\Image;
use \Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public $successStatus = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #   Panel de control
        $log = Auth::id();
        $users = User::orderBy('created_at', 'asc')->where('id',$log)->with('imagenes')->get()->toArray();
        $users=Array_chunk($users,3,true);
        $imgBorrado = array();
        $imgB=null;
        //dd($imgBorrado);
        $user=User::find('id');

        $userslist=User::all();
        //$list=User::orderBy('created_at', 'asc')->get('id','name')->toArray();
        //dd($userslist);
        return view('users.index', compact('user','users','imgBorrado','imgB','userslist'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    } 

    public function verificacion(){
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
            if($turnos == $array){
                return array(true, $id);
            }
        }

        return array(false, null);

    }


    public function actualizarHorarioPersonal(Request $request){
        $file = fopen( $request->centros, "r");
        $data = fgetcsv($file,null,';');

        //Archivo de error
        $delimiter = ";";
        mkdir("C:\CSV");
        $file_error = fopen("C:\CSV\EmpleadosNoActualizados_".uniqid().".csv", 'w');
        $fields = array('Codigo', 'DNI', 'Centro', 'Empresa', 'CIF', 'Causa');
        fputcsv($file_error, $fields, $delimiter);

        while($data = fgetcsv($file,null,';')){
            // Datos del empleado
            $cod_empleado = sprintf("%05d", $data[0]);
            $dni_empleado = $data[1];
            $cen_empleado = sprintf("%03d", $data[2]);
            $emp_empleado = sprintf("%04d", $data[3]);
            $cif_empleado = $data[4];

            //Quitamos datos no necesarios para el horario
            unset($data[0]);
            unset($data[1]);
            unset($data[2]);
            unset($data[3]);
            unset($data[4]);

            $data = array_values($data);

            // Comprobamos que existe el empleado
            $user = User::where('COD', '=', $cod_empleado)
                            ->where('EMP', '=', $emp_empleado)
                            ->where('CIF', '=', $cif_empleado)
                            ->first();
            if($user == null){
                $fields = array(
                    $cod_empleado, 
                    $dni_empleado, 
                    $cen_empleado, 
                    $emp_empleado, 
                    $cif_empleado, 
                    'El empleado no existe en la base de datos'
                );

                fputcsv($file_error, $fields, $delimiter);
            }else{
                // Comprobamos que el usuario está dado de alta en ese centro
                $centros_empleado = array(
                    $user->CEN,
                    $user->CEN_02,
                    $user->CEN_03,
                    $user->CEN_04,
                    $user->CEN_05
                );
                if(!in_array($cen_empleado, $centros_empleado)){
                    $fields = array(
                        $cod_empleado, 
                        $dni_empleado, 
                        $cen_empleado, 
                        $emp_empleado, 
                        $cif_empleado, 
                        'El empleado no pertenece a ese centro'
                    );
    
                    fputcsv($file_error, $fields, $delimiter);
                }else{  
                    // Comprobamos que el turno no este vacio
                    if($data == null){
                        $fields = array(
                            $cod_empleado, 
                            $dni_empleado, 
                            $cen_empleado, 
                            $emp_empleado, 
                            $cif_empleado, 
                            'Horario no valido'
                        );

                        fputcsv($file_error, $fields, $delimiter);
                    }else{
                        // Comprobamos si el turno existe o no
                        $result = UserController::checkHorario($data);
                        $id_turno = null;

                        if($result[0]){
                            $id_turno = $result[0];
                        }else{

                            $cont = 0;
                            foreach($data as $d){
                                if($d == ''){
                                    $data[$cont] = null;
                                }else{
                                    $max_len = (strlen($d));
                                
                                $prim = substr($d, 0, -2);
                                $seg = substr($d, -2);

                                $data[$cont] = $prim.':'.$seg;
                                }
                                
                                $cont = $cont + 1;
                            }

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

                        // Vemos que centro hay que cambiar
                        if($cen_empleado == $user->CEN){
                            $user->update([
                                'horario1' => $id_turno,
                            ]);
                        }elseif($cen_empleado == $user->CEN_02){
                            $user->update([
                                'horario2' => $id_turno,
                            ]);
                        }elseif($cen_empleado == $user->CEN_03){
                            $user->update([
                                'horario3' => $id_turno,
                            ]);
                        }elseif($cen_empleado == $user->CEN_04){
                            $user->update([
                                'horario4' => $id_turno,
                            ]);
                        }elseif($cen_empleado == $user->CEN_05){
                            $user->update([
                                'horario5' => $id_turno,
                            ]);
                        }
                        
                        //Guardamos el usuario
                        $user->save();
                    }
                    
                }
            }
        }
        fclose($file_error);
        fclose($file);
        return back();      
    }
    

function scanear_string($string)
{
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 
    return $string;
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function importCSV(Request $request){
        // Abrimos archivo de importación
        $file = fopen( $request->fichero, "r");

        // Creamos archivo de errores
        $delimiter = ";";
        $file_error = fopen("C:\CSV\EmpleadosNoDadosDeAlta_".uniqid().".csv", 'w');
        $fields = array('Codigo', 'Nombre', 'Centro', 'Empresa', 'CIF', 'Mail', 'Causa');
        fputcsv($file_error, $fields, $delimiter);

        // Saltamos cabecera
        $data = fgetcsv($file,null,';');

        while (($data = fgetcsv($file,null,';'))!=null) {

            // Datos del empleado
            $cod = sprintf("%05d", $data[0]);
            $nom = UserController::scanear_string(utf8_encode($data[1]));
            $ap1 = UserController::scanear_string(utf8_encode($data[2]));
            $ap2 = UserController::scanear_string(utf8_encode($data[3]));
            $cen = strval($data[4]);
            $dni = $data[5];
            $emp = sprintf("%04d", $data[6]);
            $cif = $data[7];
            $nom_empresa = $data[8];
            $mail = $data[9];
            $pass = Hash::make($dni);

            $actual_date = date('Y-m-d');   
            $empresa = Empresa::where('CIF', '=', $cif)->first();
            $maximo = $empresa->maximo_empleados;
            $cont_users = sizeof(User::where('CIF', $cif)->get()->toArray());

            if($cont_users >= $maximo){

                $linedata = array($cod, $nom.$ap1.$ap2, $cen, $emp, $cif, $mail, 'No hay mas espacio en alojamiento');
                fputcsv($file_error, $linedata, $delimiter);
                return back();
            }elseif($mail == ''||$mail == null){

                $linedata = array($cod, $nom.$ap1.$ap2, $cen, $emp, $cif, $mail, 'Mail vacio');
                fputcsv($file_error, $linedata, $delimiter);
            }elseif(!filter_var($mail, FILTER_VALIDATE_EMAIL)){

                $linedata = array($cod, $nom.$ap1.$ap2, $cen, $emp, $cif, $mail, 'Mail no valido');
                fputcsv($file_error, $linedata, $delimiter);
            }else{
                

                // Centros en los que se quiere dar de alta a los empleado
                $all_distinct_centros = explode(",", $cen);

                // Comprobamos que no exista ya.
                $comprobar_user = User::where('COD', '=', $cod)
                                        ->where('EMP', '=', $emp)
                                        ->where('CIF', '=', $cif)
                                        ->first();
                
                if($comprobar_user){
                    $linedata = array($cod, $nom.$ap1.$ap2, $cen, $emp, $mail, 'Ususario ya existente');
                    fputcsv($file_error, $linedata, $delimiter);
                }else{
                    $error_centro = false;

                    foreach($all_distinct_centros as $centro){
                        $comprobar_centro = Centro::where('COD', '=', sprintf("%03d", $centro))
                                                    ->where('EMP', '=', sprintf("%04d", $emp))
                                                    ->where('CIF', '=', $cif)
                                                    ->first();

                        if($comprobar_centro == null){
                            $error_centro = true;
                        }
                    }

                    if($error_centro){

                        $linedata = array($cod, $nom.$ap1.$ap2, $cen, $emp, $mail, 'Uno de los centros no existe');
                        fputcsv($file_error, $linedata, $delimiter);
                    }else{
                        //Preparamos centros para insertar en base de datos
                        $rellenar_centros = array(null, null, null, null, null);
                        $cont = 0;

                        foreach($all_distinct_centros as $centro){
                            if($centro != null){
                                $rellenar_centros[$cont] = sprintf("%03d", $centro);
                            }
                            $cont++;
                        }

                        $error_mail_repetido = false;
                        $comprobar_mail_repetido = User::all();

                        foreach($comprobar_mail_repetido as $user){
                            if($user->email == $mail){
                                $error_mail_repetido = true;
                            }
                        }

                        if($error_mail_repetido){
                            
                            $linedata = array($cod, $nom.$ap1.$ap2, $cen, $emp, $cif, $mail, 'Mail repetido');
                            fputcsv($file_error, $linedata, $delimiter);
                        }else{
                            try{
                                $user = User::create([
                                    'COD' => $cod,
                                    'NOM' => $nom,
                                    'AP1' => $ap1,
                                    'AP2' => $ap2,
                                    'CEN' => $rellenar_centros[0],
                                    'CEN_02' => $rellenar_centros[1],
                                    'CEN_03' => $rellenar_centros[2],
                                    'CEN_04' => $rellenar_centros[3],
                                    'CEN_05' => $rellenar_centros[4],
                                    'DNI' => $dni,
                                    'EMP' => $emp,
                                    'FAL' => $actual_date,
                                    'FBA' => null,
                                    'email' => $mail,
                                    'password' => $pass,
                                    'role' => 'empleado',
                                    'CIF' => $cif,
                                    'NOM_EMP' => $nom_empresa,
                                ]);
                            }catch(Exception $e){
                                $linedata = array($cod, $nom.$ap1.$ap2, $cen, $emp, $cif, $mail, $e->getMessage());
                                fputcsv($file_error, $linedata, $delimiter);
                            }
                        }
                    }
                }
            }

            
        }
        fclose($file);
        fclose($file_error);
        return back();
    }

    public function store(Request $request)
    {   
        $pr_emp = sprintf("%04d", $request->emp);
        $pr_cod = sprintf("%05d", $request->cod);
        $cif = $request->cif;
        

        // Formateo de los centros
        if($request->cen1==Null){
            $pr_cen1 = Null;
        }else{
            $pr_cen1 = sprintf("%03d", $request->cen1);
        }

        if($request->cen2==Null){
            $pr_cen2 = Null;
        }else{
            $pr_cen2 = sprintf("%03d", $request->cen2);
        }

        if($request->cen3==Null){
            $pr_cen3 = Null;
        }else{
            $pr_cen3 = sprintf("%03d", $request->cen3);
        }

        if($request->cen4==Null){
            $pr_cen4 = Null;
        }else{
            $pr_cen4 = sprintf("%03d", $request->cen4);
        }

        if($request->cen5==Null){
            $pr_cen5 = Null;
        }else{
            $pr_cen5 = sprintf("%03d", $request->cen5);
        }

        //Comprobar existencia de cenbtros

        //Comprobar centros repetidos
        $centros = array_filter( [$pr_cen1, $pr_cen2, $pr_cen3, $pr_cen4, $pr_cen5] );
        $long_original = count($centros);

        $uniques = array_unique($centros);
        $long_unicos = count($uniques);

        //Comprobar que existen esos centros
        $not_exist = true;

        $all_centros = User::orderBy('id', 'asc')->get()->toArray();
        // dd(variable)
        foreach($all_centros as $centro){

            foreach($uniques as $u){

                if($centro['COD']!=$u){
                    $not_exist = false;
                }
            }
        }

        if($not_exist){
            return back()->with('message', ['danger', __("  Centro no existente")]);
        }
        
        if($long_original > $long_unicos){
            return back()->with('message', ['danger', __("  Añadiste centros repetidos")]);
        }

        $cod_com = $pr_cod.$pr_emp.$cif;
        $actual_date = date('Y-m-d');

        if($request->rol<=>"supervisor"){
            $pr_rol = "empleado";
        }else{
            $pr_rol = "jefe";
        } 

        $pass = Hash::make($request->pwd);

        $con=mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'),env('DB_DATABASE'));

        $sql_users = "SELECT * from users where active";
        $result_users = mysqli_query($con, $sql_users);
        $rows_users = $result_users->num_rows;

        if($rows_users < 100){
            //Recogemos los resultados de las consultas sql.
            $users = $result_users->fetch_array(MYSQLI_BOTH);
            //Comprobar cod, email y dni.
            while($users!=null){

                if($users['COD'].$users['EMP'].$users['CIF']==$cod_com){
                    return back()->with('message', ['danger', __("  Codigo ya existente para empresa $pr_emp")]);
                }

                
                if($users['DNI']==$request->dni){
                    return back()->with('message', ['danger', __("  DNI ya existente")]);
                }
                

                if($users['email']==$request->mail){
                    return back()->with('message', ['danger', __("  Correo ya existente")]);
                }
                
                $users = $result_users->fetch_array(MYSQLI_BOTH);
            }


            $user = User::create([
                // Nueva columna
                'COD' => $pr_cod,
                'NOM' => $request->nom,
                'AP1' => $request->ap1,
                'AP2' => $request->ap2,
                'CEN' => $pr_cen1,
                'CEN_02' => $pr_cen2,
                'CEN_03' => $pr_cen3,
                'CEN_04' => $pr_cen4,
                'CEN_05' => $pr_cen5,
                'DNI' => $request->dni,
                'EMP' => $pr_emp,
                'CIF' => $cif,
                'FAL' => $actual_date,
                'FBA' => null,
                'email' => $request->mail,
                'password' => $pass,
                'role' => $pr_rol,
            ]);

            return back()->with('message', ['success', __("Empleado dado de alta correctamente")]);
        }else{

            return back()->with('message', ['danger', __("Tope de empleados alacanzado")]);
        }
    }

    public function cambiarCentro(Request $request){

        $id_user = $request->id;
        $user = User::find($id_user);
        $turno = NULL;

        if($request->centro==1){

            if($user->horario1!=NULL){
                $turno = Turnos::find($user->horario1);
            }
            $centro = Centro::where('COD', '=', $user->CEN)->where('EMP', '=', $user->EMP)->first();
            
        }elseif($request->centro==2){

            if($user->horario2!=NULL){
                $turno = Turnos::find($user->horario2);
            }
            $centro = Centro::where('COD', '=', $user->CEN_02)->where('EMP', '=', $user->EMP)->first();

            
        }elseif($request->centro==3){
            if($user->horario3!=NULL){
                $turno = Turnos::find($user->horario3);
            }
            $centro = Centro::where('COD', '=', $user->CEN_03)->where('EMP', '=', $user->EMP)->first();
            
        }elseif($request->centro==4){
            if($user->horario4!=NULL){
                $turno = Turnos::find($user->horario4);
            }
            $centro = Centro::where('COD', '=', $user->CEN_04)->where('EMP', '=', $user->EMP)->first();
            
        }elseif($request->centro==5){
            if($user->horario5!=NULL){
                $turno = Turnos::find($user->horario5);
            }
            $centro = Centro::where('COD', '=', $user->CEN_05)->where('EMP', '=', $user->EMP)->first();
        }
        if($turno==NULL){
            $turno = Turnos::find($centro->horario);
        }

        return view('users.show', compact('user', 'turno', 'centro'));;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    { 
        if(Auth::user()->role == 'empleado'){
            $id = Auth::user()->id;
            $user=User::find($id);
        } else {
            #   Nombre del select (input)
            $userId = $request->get('workers'); 
            #   Mostrar usuario con sus atributos
            $usu=User::find($userId);
            
            foreach($usu as $user) {
                $user->NOM;
            }

            
        }

        $primer_cen=$user->CEN;
        $centro = Centro::where('COD', '=', $primer_cen)->first();

        if($user->horario1!=null){
            $turno = Turnos::find($user->horario1);
        }else{
            $turno = Turnos::find($centro->horario);
        }

        return view('users.show', compact('user', 'turno', 'centro'));;
    }

    public function buscarUser(Request $request){

        $user = User::find($request->selectEmpleado);

        $centro = Centro::where('COD', '=', $user->CEN)
                        ->where('EMP', '=', $user->EMP)
                        ->where('CIF', '=', $user->CIF)
                        ->first();

        $id_turno = $centro->horario;

        if($user->horario1 != null){
            $id_turno = $user->horario1;
        }

        $turno = Turnos::find($id_turno);
        
        return view('users.show', compact('user', 'turno', 'centro'));
    }

    public function inicio(){
        if(Auth::user()->role=='jefe'){
            $users = User::where('CIF', '=', Auth::user()->CIF)->orderByRaw('EMP DESC')->orderByRaw('COD ASC')->get()
                                            ->toArray();
        }else{
            $users = User::orderByRaw('EMP DESC')->orderByRaw('COD ASC')->get()
            ->toArray();
        }
        
        if(Auth::user()->role=='jefe'){
            $u = $users[0];
            $centro = Centro::where('COD', '=', $u['CEN'])->first();
            if($u['horario1']!=null){
                $turno = Turnos::find($u['horario1']);
            }else{
                $turno = Turnos::find($centro->horario);
            }
            $user = User::find($u["id"]);
            return view('users.show', compact('user', 'turno', 'centro'));   
        }else{
            $all_cifs = Empresa::where('id_supervisor', '=', Auth::user()->id)->get("CIF")->toArray();
            $cont = 0;
            foreach($all_cifs as $cif){
                $all_cifs[$cont] = $cif["CIF"];
                $cont++;
            }

            foreach($users as $u){
                if($u['role'] != 'supervisor'){
                    
                    if(in_array($u['CIF'], $all_cifs)){
                        
                        $centro = Centro::where('COD', '=', $u['CEN'])->first();
                        if($u['horario1']!=null){
                            $turno = Turnos::find($u['horario1']);
                        }else{
                            $turno = Turnos::find($centro->horario);
                        }
                        $user = User::find($u["id"]);
                        return view('users.show', compact('user', 'turno', 'centro'));   
                    }
                
                }
            }
        }
        


         
    }

    public function fin(){
        if(Auth::user()->role=='jefe'){
            $users = User::where('CIF', '=', Auth::user()->CIF)->orderByRaw('EMP DESC')->orderByRaw('COD DESC')->get()
                                            ->toArray();
        }else{
            $users = User::orderByRaw('EMP DESC')->orderByRaw('COD DESC')->get()
            ->toArray();
        }
        if(Auth::user()->role=='jefe'){
            $u = $users[0];
            $centro = Centro::where('COD', '=', $u['CEN'])->first();
            if($u['horario1']!=null){
                $turno = Turnos::find($u['horario1']);
            }else{
                $turno = Turnos::find($centro->horario);
            }
            $user = User::find($u["id"]);
            return view('users.show', compact('user', 'turno', 'centro'));   
        }else{
            $all_cifs = Empresa::where('id_supervisor', '=', Auth::user()->id)->get("CIF")->toArray();
            $cont = 0;
            foreach($all_cifs as $cif){
                $all_cifs[$cont] = $cif["CIF"];
                $cont++;
            }

            foreach($users as $u){
                if($u['role'] != 'supervisor'){
                    
                    if(in_array($u['CIF'], $all_cifs)){
                        
                        $centro = Centro::where('COD', '=', $u['CEN'])->first();
                        if($u['horario1']!=null){
                            $turno = Turnos::find($u['horario1']);
                        }else{
                            $turno = Turnos::find($centro->horario);
                        }
                        $user = User::find($u["id"]);
                        return view('users.show', compact('user', 'turno', 'centro'));   
                    }
                
                }
            }  
        }
    }

    public function back(Request $request)
    {   
        $user = User::find($request->usuario)->toArray();
        
        if(Auth::user()->role=='jefe'){
            $all_user=User::where('CIF', '=', Auth::user()->CIF)->get()->toArray();
        }else{
            $all_user=User::get()->toArray();
        }

        $indice_user = array_search($user, $all_user);

        $cont = $indice_user - 1;
        
        if($cont < 0){
            $u = $user;

            $centro = Centro::where('COD', '=', $u['CEN'])->first();
            if($u['horario1']!=null){
                $turno = Turnos::find($u['horario1']);
            }else{
                $turno = Turnos::find($centro->horario);
            }
            $user = User::find($u["id"]);

            return view('users.show', compact('user', 'turno', 'centro'));
        }

        if(Auth::user()->role=='jefe'){
            $u = $all_user[$cont];

            $centro = Centro::where('COD', '=', $u['CEN'])->first();
            if($u['horario1']!=null){
                $turno = Turnos::find($u['horario1']);
            }else{
                $turno = Turnos::find($centro->horario);
            }
            $user = User::find($u["id"]);

            return view('users.show', compact('user', 'turno', 'centro'));
        }else{
            while($cont >= 0){
                $u = $all_user[$cont];
                $all_cifs = Empresa::where('id_supervisor', '=', Auth::user()->id)->get("CIF")->toArray();
                $cont = 0;
    
                foreach($all_cifs as $cif){
                    $all_cifs[$cont] = $cif["CIF"];
                    $cont++;
                }
    
                if(in_array($u["CIF"], $all_cifs)){
                    $centro = Centro::where('COD', '=', $u['CEN'])->first();
                    if($u['horario1']!=null){
                        $turno = Turnos::find($u['horario1']);
                    }else{
                        $turno = Turnos::find($centro->horario);
                    }
                    $user = User::find($u["id"]);
                    return view('users.show', compact('user', 'turno', 'centro'));
                }
                
                $cont--;
            } 
        }
    }

    public function next(Request $request)
    {   
        $user = User::find($request->usuario)->toArray();
        
        if(Auth::user()->role=='jefe'){
            $all_user=User::where('CIF', '=', Auth::user()->CIF)->get()->toArray();
        }else{
            $all_user=User::get()->toArray();
        }

        $indice_user = array_search($user, $all_user);

        $cont = $indice_user + 1;

        if($cont >= sizeof($all_user)){
            $u = $user;

            $centro = Centro::where('COD', '=', $u['CEN'])->first();
            if($u['horario1']!=null){
                $turno = Turnos::find($u['horario1']);
            }else{
                $turno = Turnos::find($centro->horario);
            }
            $user = User::find($u["id"]);
            return view('users.show', compact('user', 'turno', 'centro'));

        }

        if(Auth::user()->role=='jefe'){
            $u = $all_user[$cont];

            $centro = Centro::where('COD', '=', $u['CEN'])->first();
            if($u['horario1']!=null){
                $turno = Turnos::find($u['horario1']);
            }else{
                $turno = Turnos::find($centro->horario);
            }
            $user = User::find($u["id"]);

            return view('users.show', compact('user', 'turno', 'centro'));
        }else{
            while($cont <= sizeof($all_user)){
                $u = $all_user[$cont];
                $all_cifs = Empresa::where('id_supervisor', '=', Auth::user()->id)->get("CIF")->toArray();
                $cont_emp = 0;
    
                foreach($all_cifs as $cif){
                    $all_cifs[$cont_emp] = $cif["CIF"];
                    $cont_emp++;
                }
    
                if(in_array($u["CIF"], $all_cifs)){
                    $centro = Centro::where('COD', '=', $u['CEN'])->first();
                    if($u['horario1']!=null){
                        $turno = Turnos::find($u['horario1']);
                    }else{
                        $turno = Turnos::find($centro->horario);
                    }
                    $user = User::find($u["id"]);
                    return view('users.show', compact('user', 'turno', 'centro'));
                }
                
                $cont++;
            } 
        }
        

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        //Actualizamos cada campo comropband si es el que se desea actualizar

        $user_emp = sprintf("%04d", $request->emp);

        if($request->cod != null){
            $cod_com = $user_emp.$request->cod;

            $user->update([
                'COD_COM' => $cod_com,
                'COD' => $request->cod,
            ]);
            $user->save();
        }

        if($request->name != null){
            $user->update([
                'NOM' => $request->name,
            ]);
            $user->save();
        }

        if($request->ap1 != null){
            $user->update([
                'AP1' => $request->ap1,
            ]);
            $user->save();
        }

        if($request->ap2 != null){
            $user->update([
                'AP2' => $request->ap2,
            ]);
            $user->save();
        }

        if($request->cen1 != null){
            $user_cen1 = sprintf("%03d", $request->cen1);
            $comp_cen1 = Centro::find($user_cen1);
            if($comp_cen1==null){
                return back()->with('message', ['danger', __("El centro $user_cen1 no existe")]);
            }
            $user->update([
                'CEN' => $user_cen1,
            ]);
            $user->save();
        }

        if($request->cen2 != null){
            $user_cen2 = sprintf("%03d", $request->cen2);
            $comp_cen2 = Centro::find($user_cen2);
            if($comp_cen2==null){
                return back()->with('message', ['danger', __("El centro $user_cen2 no existe")]);
            }
            $user->update([
                'CEN_02' => $user_cen2,
            ]);
            $user->save();
        }

        if($request->cen3 != null){
            $user_cen3 = sprintf("%03d", $request->cen3);
            $comp_cen3 = Centro::find($user_cen3);
            if($comp_cen3==null){
                return back()->with('message', ['danger', __("El centro $user_cen3 no existe")]);
            }
            $user->update([
                'CEN_03' => $user_cen3,
            ]);
            $user->save();
        }else{
            $user->update([
                'CEN_03' => null,
            ]);
            $user->save();
        }

        if($request->cen4 != null){
            $user_cen4 = sprintf("%03d", $request->cen4);
            $comp_cen4 = Centro::find($user_cen4);
            if($comp_cen4==null){
                return back()->with('message', ['danger', __("El centro $user_cen4 no existe")]);
            }
            $user->update([
                'CEN_04' => $user_cen4,
            ]);
            $user->save();
        }else{
            $user->update([
                'CEN_04' => null,
            ]);
            $user->save();
        }

        if($request->cen5 != null){
            $user_cen5 = sprintf("%03d", $request->cen5);
            $comp_cen5 = Centro::find($user_cen5);
            if($comp_cen5==null){
                return back()->with('message', ['danger', __("El centro $user_cen5 no existe")]);
            }
            $user->update([
                'CEN_05' => $user_cen5,
            ]);
            $user->save();
        }else{
            $user->update([
                'CEN_05' => null,
            ]);
            $user->save();
        }

        if($request->dni != null){
            $user->update([
                'DNI' => $request->dni,
            ]);
            $user->save();
        }

        if($request->fal != null){
            $user->update([
                'FAL' => $request->fal,
            ]);
            $user->save();
        }

        if($request->emp != null){
            $user->update([
                'EMP' => $request->emp,
            ]);
            $user->save();
        }

        if($request->email != null){
            $user->update([
                'email' => $request->email,
            ]);
            $user->save();
        }
        // Eliminar imágenes si existiera perfil anterior
        if($request->imgBorrado != null){
            $imgBorrado = explode(',',$request->imgBorrado); 
            foreach($imgBorrado as $i){
                $valor = intval($i);
                $imagen = Image::find($valor);
                Storage::disk('documents')->delete($imagen->img);
                $imagen->delete();
            }
        }

        // Sustituir anterior imagen de perfil por la nueva que se sube
        if($request->img != null) {
            $imagen_anterior=Image::orderBy('created_at','DESC')->where('id_worker',$id)->delete();
        }

        //  Añadiendo nueva imagen
        if(isset($request->img)){
            foreach($request->img as $img){
                $file = $img;
                $nombre = $file->getClientOriginalName();
                Storage::disk('documents')->put($nombre,  \File::get($file));

                Image::create([
                    'id_worker' => $user->id,
                    'img' => $nombre,
                ]);
            }
        }
        // Mensaje varía en función de qué actualice
        if($request->img != null) {
            return back()->with('message', ['info', __("Foto de perfil actualizada correctamente")]);
        } 
        else {
           return back()->with('message', ['success', __("Información de perfil actualizada correctamente")]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect('/users?admin')->with('success', 'Usuario eliminado con éxito.');
    }
}