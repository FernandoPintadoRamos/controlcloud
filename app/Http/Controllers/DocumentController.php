<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Document;
use App\User;
use App\Empresa;
use App\Agenda;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Storage;
// use App\Http\Requests\DocumentsRequest;



class DocumentController extends Controller
{

    public function mostrarNominas(Request $request){
        $file = fopen($request->fichero, 'r');
        $delimiter = ';';
        $data = fgetcsv($file,null,$delimiter);

        while(($data = fgetcsv($file,null,$delimiter)) != null){
            $document = Document::where('doc', $data['5'])->first();
            $document->update([
                'oculto' => 0,
            ]);
            $document->save();
        }

        return back();
    }

    public function borrarNominas(Request $request){
        $file = fopen($request->fichero, 'r');
        $delimiter = ';';
        $data = fgetcsv($file,null,$delimiter);

        while(($data = fgetcsv($file,null,$delimiter)) != null){
            $document = Document::where('doc', $data['5'])->first();
            $document->delete();
        }

        return back();
    }
    
    public function downloadFile(Request $req){
        $path = storage_path().'/'.'app'.'/documents/'.$req->nombre;
        if (file_exists($path)) {
            return response()->download($path);
        }
    }

    public function obtenerRuta($file){
        $delimiter = ';';
        $data = fgetcsv($file,null,$delimiter);
        $data = fgetcsv($file,null,$delimiter);
        $ruta = $data[5];
        $ruta_array = explode('\\', $ruta);
        $ruta = '';
        for($i = 0; $i < (sizeof($ruta_array)-1); $i++){
            $ruta = $ruta.$ruta_array[$i].'\\';
        }
        return ($ruta);
    }


    public function nomina(Request $req){
        $file = fopen($req->fichero, "r");
        $file_para_ruta = fopen($req->fichero, "r");
        $delimiter = ';';
        $data = fgetcsv($file,null,$delimiter);

        //Creamos directorio donse se guardan los archivos de salida
        //Recogemos la ruta
        $ruta = DocumentController::obtenerRuta($file_para_ruta);
        $path_error = $ruta.'Erroneas';
        $path_success = $ruta.'Correctas';
        $path_gestion = "C:\\Gestion_Documental";

        if(!is_dir($path_gestion)){
            mkdir($path_gestion, 0777, true);
        }

        if (!is_dir($path_error)) {
            mkdir($path_error, 0777, true);
        }

        if (!is_dir($path_success)) {
            mkdir($path_success, 0777, true);
        }
        

        // Archivo de salida error
        $file_error = fopen($path_error."\\NominasNoSubidas".uniqid().".csv", 'w');
        $fields = array('Codigo Empleado','DNI', 'CIF empresa', 'Código Empresa', 'Mes', 'Ruta', 'Causa');
        fputcsv($file_error, $fields, $delimiter);

        // Archivo de salida correcta
        $file_success = fopen($path_success."\\NominasSubidas".uniqid().".csv", 'w');
        $fields = array('Codigo Empleado','DNI', 'CIF empresa', 'Código Empresa', 'Mes', 'Ruta');
        fputcsv($file_success, $fields, $delimiter);
        

        while (($data = fgetcsv($file,null,';'))!=null) {
            // Datos a recoger  
            $cod_empleado = sprintf("%05d", $data[0]);
            $dni_empleado = $data[1];
            $cif_empresa = $data[2];
            $cod_empresa =  sprintf("%04d", $data[3]);
            $mes = $data[4];
            $ruta = $data[5];


            // Control de errores
            //Comprobar que el documento existe
            if(!file_exists($ruta)){
                $fields = array(
                    $cod_empleado,
                    $dni_empleado,
                    $cif_empresa,
                    $cod_empresa,
                    $mes,
                    $ruta,
                    'El documento no existe'
                );
                fputcsv($file_error, $fields, $delimiter);
            }else{
                $user = User::where('CIF', $cif_empresa)->
                                where('EMP', $cod_empresa)->
                                where('COD', $cod_empleado)->
                                where('DNI', $dni_empleado)->
                                first();
                if($user == null){
                    $fields = array(
                        $cod_empleado,
                        $dni_empleado,
                        $cif_empresa,
                        $cod_empresa,
                        $mes,
                        $ruta,
                        'El empleado no existe'
                    );
                    fputcsv($file_error, $fields, $delimiter);
                }else{
                    $ruta_array = explode('\\', $ruta);
                    $nombre_archivo_ext = $ruta_array[sizeof($ruta_array)-1];
                    $nombre_archivo = explode('.', $nombre_archivo_ext)[0];
                    $extension = explode('.', $nombre_archivo_ext)[1];
                    $nombre_archivo = intval($nombre_archivo);
                    $cod_empleado = intval($cod_empleado);
                    if($nombre_archivo != $cod_empleado){
                        $fields = array(
                            $cod_empleado,
                            $dni_empleado,
                            $cif_empresa,
                            $cod_empresa,
                            $mes,
                            $ruta,
                            'El nombre no coincide con el codigo del empleado'
                        );
                        fputcsv($file_error, $fields, $delimiter);
                    }else{  
                        

                        $empresa = Empresa::where('CIF', $cif_empresa)->first();
                        $usuario_ftp = $empresa->usuario_ftp;
                        $path_actual = $path_gestion.'\\'.$usuario_ftp;

                        $mes_archivo = substr($mes, 0, 2);
                        $tipo_nomina = substr($mes, 2, 1);

                        $año_actual = date("Y");
                        $año = (substr($año_actual, 0, 2)).(substr($mes, 3, 2));

                        $nuevo_nom_archivo = $dni_empleado.'_'.$año.'_'.$mes_archivo.'_'.'NO';
                        if($tipo_nomina == 'E'){
                            if(substr($mes, strlen($mes)-2, 2) == 'EO'){
                                $nuevo_nom_archivo = $dni_empleado.'_'.$año.'_'.$mes_archivo.'_'.'NEEO';
                                $nueva_ruta_archivo = $path_actual.'\\'.$nuevo_nom_archivo.'.'.$extension;

                            }else{
                                $nueva_ruta_archivo = $path_actual.'\\'.$nuevo_nom_archivo.'EX'.'.'.$extension;
                            }
                        }

                        if($tipo_nomina=='N'){
                            $nueva_ruta_archivo = $path_actual.'\\'.$nuevo_nom_archivo.'MI'.'.'.$extension;
                            if(strlen($mes) > 5){
                                $nueva_ruta_archivo = $path_actual.'\\'.$nuevo_nom_archivo.substr($mes, 5, 2).'.'.$extension;
                            }
                        }

                        if($tipo_nomina == 'A'){
                            $nueva_ruta_archivo = $path_actual.'\\'.$nuevo_nom_archivo.'AT'.'.'.$extension;
                            if(strlen($mes) > 5){
                                $nuevo_nom_archivo = $dni_empleado.'_'.$año.'_'.$mes_archivo.'_'.'NA';
                                $nueva_ruta_archivo = $path_actual.'\\'.$nuevo_nom_archivo.substr($mes, 5, 2).'.'.$extension;
                            }
                        }

                        if($req->sobrescribir == null){
                            $array_nueva_ruta = explode('.', $nueva_ruta_archivo);
                            $nueva_ruta_archivo = $array_nueva_ruta[0].'_'.uniqid().'.'.$array_nueva_ruta[sizeof($array_nueva_ruta)-1];
                        }

                        $nuevo_nom_archivo = explode('\\', $nueva_ruta_archivo);
                        $nuevo_nom_archivo = $nuevo_nom_archivo[sizeof($nuevo_nom_archivo)-1];

                        //FTP
                        $uri = "ftp://copiasrar.hknominas.es:Appnomina123456@ftp.hknominas.es";

                        $connection = ftp_connect("ftp.hknominas.es");
                        $login = ftp_login($connection, "copiasrar.hknominas.es", "Appnomina123456");

                        $uri = $uri.'/'.$usuario_ftp;
                        $ruta_ftp = $usuario_ftp;

                        if(!is_dir($uri)){
                            $crear = ftp_mkdir($connection, $usuario_ftp);
                        }

                        $uri = $uri.'/'.$año; 
                        $ruta_ftp = $ruta_ftp.'/'.$año;

                        if(!is_dir($uri)){
                            $crear = ftp_mkdir($connection, $usuario_ftp.'/'.$año);
                        }
                        $uri = $uri.'/'.$mes_archivo;
                        $ruta_ftp = $ruta_ftp.'/'.$mes_archivo;

                        if(!is_dir($uri)){
                            $crear = ftp_mkdir($connection, $usuario_ftp.'/'.$año.'/'.$mes_archivo);
                        }

                        $ruta_ftp = $ruta_ftp.'/'.$nuevo_nom_archivo;

                        $put = ftp_put($connection, $ruta_ftp, $ruta);
                        
                        $size = round(filesize($path_actual.'\\'.$nuevo_nom_archivo) / 1000000, 2);

                        if($req->oculto != null){
                            $oculto = 1;
                        }else{
                            $oculto = 0;
                        }


                        $document = Document::create([
                            'id_worker'     => $user->id,
                            'tipo'          => 'nomina',
                            'doc'           => $ruta_ftp,
                            'description'   => 'Nómina',
                            'size'          => $size,
                            'oculto'        => $oculto,
                            'CIF'           => $cif_empresa,
                        ]);

                        if($oculto == 0){

                            $fecha = date("m-d-y");
                            $hora = date("H:i:s");     

                            $aviso = Agenda::create([
                                'usuario_receptor'      => $user->id,
                                'usuario_transmisor'    => Auth::user()->id,
                                'asunto'                => 'Nueva Nomina',
                                'contenido'             => 'Nómina',
                                'visto'                 => false,
                                'fecha'                 => $fecha,
                                'hora'                  => $hora,
                                'id_archivo'            => $document->id
                            ]);
                        }

                        $fields = array(
                            $cod_empleado,
                            $dni_empleado,
                            $cif_empresa,
                            $cod_empresa,
                            $mes,
                            $ruta_ftp,
                        );
                        fputcsv($file_success, $fields, $delimiter);
                    }
                }
            }
        }

        
        fclose($file);
        fclose($file_error);
        fclose($file_success);

        return back();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario       = Auth::id();
        $users         = User::all();
        $use           = User::find($usuario);  // Nombre empleado logueado
        if($use->role == 'empleado'){
            $documents = Document::orderBy('created_at', 'ASC')->where('id_worker', $usuario)->paginate(4);
        } else {
            $documents = Document::orderBy('created_at', 'ASC')->paginate(6);
        }

        return view('documents/index', compact('documents', 'use', 'users'));
    }

    public function verArchivo(Request $request){
        $document = Document::where('id', '=', $request->id_archivo)->first();
        $documents = Document::where('id', '=', $request->id_archivo)->paginate(4);
        $usuario = $document->id_worker;
        $users = User::all();
        $use = User::find($usuario);

        return view('documents/general', compact('documents', 'use', 'users'));
    }

    public function general(){
        $tipo          = 'General';
        $usuario       = Auth::id();
        $users         = User::all();
        $use           = User::find($usuario);  // Nombre empleado logueado
        if($use->role == 'empleado'){
            $documents = Document::orderBy('created_at', 'ASC')->where('id_worker', $usuario)->paginate(4);
        }elseif($use->role == 'jefe'){
            $documents = Document::orderBy('created_at', 'ASC')->where('CIF', '=', $use->CIF)->paginate(4);
        } else {
            $documents_todos = Document::orderBy('created_at', 'ASC')->paginate(6);
            $documents = array();
            $all_cifs = Empresa::where('id_supervisor', Auth::user()->id)->get('CIF')->toArray();
            $cont_emp = 0;
            foreach($all_cifs as $cif){
                $all_cifs[$cont_emp] = $cif['CIF'];
                $cont_emp++;
            }
            foreach($documents_todos as $d){
                if(in_array($d['CIF'], $all_cifs)){
                    array_push($documents, $d);
                }
            }
        }

        return view('documents/general', compact('documents', 'use', 'users', 'tipo'));
    }
    
    public function nominas(){
        $tipo          = 'General';
        $usuario       = Auth::id();
        $users         = User::all();
        $use           = User::find($usuario);  // Nombre empleado logueado
        if($use->role == 'empleado'){
            $documents = Document::orderBy('created_at', 'ASC')->where('oculto', 0)->where('id_worker', $usuario)->where('tipo', '=', 'nomina')->paginate(4);
        }elseif($use->role == 'jefe'){
            $documents = Document::orderBy('created_at', 'ASC')->where('oculto', 0)->where('CIF', '=', $use->CIF)->where('tipo', '=', 'nomina')->paginate(4);
        } else {
            $documents_todos = Document::orderBy('created_at', 'ASC')->where('oculto', 0)->where('tipo', '=', 'nomina')->paginate(6);
            $documents = array();
            $all_cifs = Empresa::where('id_supervisor', Auth::user()->id)->get('CIF')->toArray();
            $cont_emp = 0;
            foreach($all_cifs as $cif){
                $all_cifs[$cont_emp] = $cif['CIF'];
                $cont_emp++;
            }
            foreach($documents_todos as $d){
                if(in_array($d['CIF'], $all_cifs)){
                    array_push($documents, $d);
                }
            }
        }

        return view('documents/general', compact('documents', 'use', 'users', 'tipo'));
    }

    public function documentosCertificados(){
        $tipo          = 'General';
        $usuario       = Auth::id();
        $users         = User::all();
        $use           = User::find($usuario);  // Nombre empleado logueado
        if($use->role == 'empleado'){
            $documents = Document::orderBy('created_at', 'ASC')->where('id_worker', $usuario)->where('tipo', '=', 'documentacion')->paginate(4);
        }elseif($use->role == 'jefe'){
            $documents = Document::orderBy('created_at', 'ASC')->where('CIF', '=', $use->CIF)->where('tipo', '=', 'documentacion')->paginate(4);
        } else {
            $documents_todos = Document::orderBy('created_at', 'ASC')->where('tipo', '=', 'documentacion')->paginate(6);
            $documents = array();
            $all_cifs = Empresa::where('id_supervisor', Auth::user()->id)->get('CIF')->toArray();
            $cont_emp = 0;
            foreach($all_cifs as $cif){
                $all_cifs[$cont_emp] = $cif['CIF'];
                $cont_emp++;
            }
            foreach($documents_todos as $d){
                if(in_array($d['CIF'], $all_cifs)){
                    array_push($documents, $d);
                }
            }
        }

        return view('documents/general', compact('documents', 'use', 'users', 'tipo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $documents = Document::all();

        return view('documents/create', compact('documents'));
    }

    

    public function adjuntar()
    {
        $documents = Document::all();

        return view('documents/adjuntar', compact('documents'));
    }

    public function adjuntarNominas()
    {
        $documents = Document::all();

        return view('documents/adjuntarNominas', compact('documents'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $req)
    {
        $tipo = "general";
        if(intval($req->get('tipo'))==1){
            $tipo = "nomina";
        }
        if(intval($req->get('tipo'))==2){
            $tipo = "certificado";
        }
        if(intval($req->get('tipo'))==3){
            $tipo = "documentacion";
        }
        $user = Auth::user();
        // Obtenemos el campo file definido en el formulario
        
        $doc = $req->file('doc');
        
        // Obtenemos el nombre del archivo
        $nombre = $doc->getClientOriginalName();
        $req->doc = $nombre;

        $size = round(filesize($doc) / 1000000, 2); // Pasados a MB

        
        if($size>20){
            return redirect()->route('documents.index')->with('message', ['danger', __("Archivo demasiado grande")]);
        }else{
            // Indicamos que queremos guardar un nuevo archivo en el disco local
            \Storage::disk('documents')->put($nombre,  \File::get($doc));

            //Comprobamos el tamaño ocupado individualmente
            $single_storage = 0;
            // $marcajes = Marcajes::orderBy('created_at', 'asc')->where('id_worker',$usuario)->get()->toArray();
            $documents = Document::orderBy('id')->where('id_worker', $user->id)->get()->toArray();

            /**
             * Recorre todos los documentos de un user para ver cuanto estan ocupandos SUS archivo.
             */
            foreach($documents as $d){
                $single_storage = $single_storage + $d['size'];
            }
            $single_storage = $single_storage + $size;

            /**
             * Comprueba si con el archivo que va a subir supera el cupo de espacio que puede ocupar.
             */
            if($single_storage > 20){
                return redirect()->route('documents.index')->with('message', ['danger', __("Capacidad máxima alcanzada")]);
            }else{
                
                if($req->selectEmpleado != null){
                    $use = User::find($req->selectEmpleado);

                    $document = Document::create([
                        'doc'           => $req->doc,
                        'description'   => $req->description,
                        'id_worker'     => $use->id,
                        'size'          => $size,
                        'CIF'           => $use->CIF,
                        'tipo'          => $tipo
                    ]);

                    $fecha = date('d/m/Y');
                    $hora = date('H:i');

                    $aviso = Agenda::create([
                        'usuario_receptor'      => $use->id,
                        'usuario_transmisor'    => Auth::user()->id,
                        'asunto'                => 'Nuevo/a '.$tipo,
                        'contenido'             => $req->description,
                        'visto'                 => false,
                        'fecha'                 => $fecha,
                        'hora'                  => $hora,
                        'id_archivo'            => $document->id
                    ]);
                }else{

                    if($req->avisarSup == "on"){
                        $cif = Auth::user()->CIF;
                        $empresa = Empresa::where('CIF', '=', $cif)->first();
                        $id_sup = $empresa->id_supervisor;

                        $document = Document::create([
                            'doc'           => $req->doc,
                            'description'   => $req->description,
                            'id_worker'     => Auth::user()->id,
                            'size'          => $size,
                            'CIF'           => $cif,
                            'tipo'          => $tipo
                        ]);
    
                        $fecha = date('d/m/Y');
                        $hora = date('H:i');
    
                        $aviso = Agenda::create([
                            'usuario_receptor'      => $id_sup,
                            'usuario_transmisor'    => Auth::user()->id,
                            'asunto'                => 'Nuevo/a '.$tipo,
                            'contenido'             => $req->description,
                            'visto'                 => false,
                            'fecha'                 => $fecha,
                            'hora'                  => $hora,
                            'id_archivo'            => $document->id
                        ]);
                    }else{
                        $document = Document::create([
                            'doc'           => $req->doc,
                            'description'   => $req->description,
                            'id_worker'     => Auth::user()->id,
                            'size'          => $size,
                            'CIF'           => Auth::user()->CIF,
                            'tipo'          => $tipo
                        ]);
                    }
                }
            }
        }

        return redirect()->route('documents.index')->with('message', ['success', __("Documento subido con éxito")]);
    }

    public function add(Request $req)
    {
        $user = Auth::user();
        // Obtenemos el campo file definido en el formulario
        
        $doc = $req->file('doc');
        // Obtenemos el nombre del archivo
        
        $nombre = $doc->getClientOriginalName();
        $req->doc = $nombre;
        

        $size = round(filesize($doc) / 1000000, 2); // Pasados a MB

        
        if($size>20){
            return redirect()->route('documents.index')->with('message', ['danger', __("Archivo demasiado grande")]);
        }else{
            // Indicamos que queremos guardar un nuevo archivo en el disco local
            \Storage::disk('documents')->put($nombre,  \File::get($doc));

            //Comprobamos el tamaño ocupado individualmente
            $single_storage = 0;
            // $marcajes = Marcajes::orderBy('created_at', 'asc')->where('id_worker',$usuario)->get()->toArray();
            $documents = Document::orderBy('id')->where('id_worker', $user->id)->get()->toArray();

            /**
             * Recorre todos los documentos de un user para ver cuanto estan ocupandos SUS archivo.
             */
            foreach($documents as $d){
                $single_storage = $single_storage + $d['size'];
            }
            $single_storage = $single_storage + $size;

            /**
             * Comprueba si con el archivo que va a subir supera el cupo de espacio que puede ocupar.
             */
            if($single_storage > 20){
                return redirect()->route('documents.index')->with('message', ['danger', __("Capacidad máxima alcanzada")]);
            }else{
                
                $use = User::where('COD', '=', sprintf("%05d", $req->cod))->where('EMP', '=', $req->emp)->first();


                $document = Document::create([
                    'doc'           => $req->doc,
                    'description'   => $req->description,
                    'id_worker'     => $use->id,
                    'size'          => $size,
                ]);
                
                return redirect()->route('documents.index')->with('message', ['success', __("Documento subido con éxito")]);

            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $document = Document::find($id);
        $document->delete();
        
        if($document)
            return back()->with('message', ['success', __("Documento borrado correctamente")]);
        else
            return back()->with('message', ['danger', __("Error al borrar el Documento")]);
    }
}
