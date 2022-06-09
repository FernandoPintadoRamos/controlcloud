<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Absentismos;
use App\Document;
use App\Empresa;
use Illuminate\Support\Facades\Auth;



class AbsentismosController extends Controller
{
    //
    public function aceptar(Request $request){
        $id = $request->id_ab_acp;
        $a = Absentismos::find($id);
        $a->update([
            'aceptado' => 1
        ]);
        $a->save();
        return back();
    }

    public function rechazar(Request $request){
        $id = $request->id_ab_rec;
        $a = Absentismos::find($id);
        $a->delete();
        return back();
    }

    public function show(){
        return view('absentismos/index');
    }

    public function solicitud(){
        $año = intval(date("Y"));
        return view('absentismos/solicitar', compact('año'));
    }

    public function ver(){
        $año = intval(date("Y"));
        return view('absentismos/verEmp', compact('año'));
    }

    public function verAdmin(){
        $año = intval(date("Y"));
        return view('absentismos/verAdmin', compact('año'));

    }

    public function cambAño(Request $request){
        $año = intval($request->input('año'));
        return view('absentismos/solicitar', compact('año'));
        
    }

    public function asignarFaltas(){
        $año = intval(date("Y"));
        return view('absentismos/asignFaltas', compact('año'));
    }

    public function ponerFaltas(Request $request){
        $desde = explode('/', $request->fecha_desde);
        $fecha_desde = $desde[2].'-'.$desde[1].'-'.$desde[0];

        
        $hasta = explode('/', $request->fecha_hasta);
        $fecha_hasta = $hasta[2].'-'.$hasta[1].'-'.$hasta[0];

        $tipo = explode('-', $request->tipo)[1];
        $tipo_it = $request->tipo_it;
        $id_empleado = intval($request->select_empleado);

        // Obtenemos el id del supervisor
        $cif = $request->select_cif;
        $empresa = Empresa::where('CIF', $cif)->first();
        $id_supervisor = $empresa->id_supervisor;

        $doc = $request->file('file');

        if($doc != null){

            //Almacenamos el documento
            $nombre_file = $doc->getClientOriginalName();
            $size = round(filesize($doc) / 1000000, 2);

            $document = Document::create([
                'doc'           => $nombre_file,
                'description'   => '',
                'tipo'          => $tipo,
                'id_worker'     => Auth::user()->id,
                'size'          => $size,
            ]);

            //Recogemos id del documento
            $id_document = $document->id;
        }else{
            $id_document = null;
        }

        // Creamos el absentismo
        Absentismos::create([
            'id_worker'     => $id_empleado,
            'tipo'          => $tipo,
            'descripcion'   => $tipo_it,
            'aceptado'      => 1,
            'desde'         => $fecha_desde,
            'hasta'         => $fecha_hasta,
            'supervisor'    => $id_supervisor,
            'id_document'   => $id_document,
        ]);
        return back();
    }

    public function modAbs(Request $request){
        $id_abs = intval($request->id_ab_mod);
        $desde = $request->fecha_desde;
        $hasta = $request->fecha_hasta;

        $ab = Absentismos::find($id_abs);

        $ab->update([
            'desde'     => $desde,
            'hasta'     => $hasta
        ]);

        $ab->save();

        return back();
    }

    public function cambAñoAdmin(Request $request){
        $año = strval($request->input('anio'));
        return view('absentismos/verAdmin', compact('año'));
    }

    public function envSol(Request $request){
        // Recogemos datos de la solicitud
        
        $desde = explode('/', $request->fecha_desde);
        $desde = $desde[2].'-'.$desde[1].'-'.$desde[0];

        $hasta = explode('/', $request->fecha_hasta);
        $hasta = $hasta[2].'-'.$hasta[1].'-'.$hasta[0];
        

        $tipo = explode('-', $request->tipo)[1];
        $tipo_it = $request->tipo_it;

        $doc = $request->file('file');

        if($doc != null){

            //Almacenamos el documento
            $nombre_file = $doc->getClientOriginalName();
            $size = round(filesize($doc) / 1000000, 2);

            $document = Document::create([
                'doc'           => $nombre_file,
                'description'   => '',
                'tipo'          => $tipo,
                'id_worker'     => Auth::user()->id,
                'size'          => $size,
            ]);

            //Recogemos id del documento
            $id_document = $document->id;
        }else{
            $id_document = null;
        }

        //Almacenamos solicitud
        $aceptado = 0;
        

        $cif = Auth::user()->CIF;
        $empresa = Empresa::where('CIF', $cif)->first();

        Absentismos::create([
            'id_worker'     => Auth::user()->id,
            'id_document'   => $id_document,
            'tipo'          => $tipo,
            'descripcion'   => $tipo_it,
            'aceptado'      => $aceptado,
            'desde'         => $desde,
            'hasta'         => $hasta,
            'supervisor'    => $empresa->id_supervisor,
        ]);

        return back();
        
    }

    public function volver(){
        return view('absentismos/index');
    }
}
