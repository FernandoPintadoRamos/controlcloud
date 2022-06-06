<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AbsenteeismsRequest;
use App\Absenteeism;
use App\Absence;
use App\User;

class AbsenteeismsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Desplegará un menú con las vacaciones y los permisos del usuario
        $usuario       = Auth::id();
        $use           = User::find($usuario);  // Nombre empleado logueado
        $absences      = Absence::all();
        $users         = User::all();     // El supervisor necesita tener acceso a todos los usuarios
        
        if($use->role == 'empleado') {
            $absenteeism = Absenteeism::where('id_worker',$usuario)->get()->toArray();
        }
        else {
            $absenteeism = Absenteeism::orderBy('id_worker', 'ASC')->where('justify', 0)->get()->toArray();
        }

        $absenteeism=Array_chunk($absenteeism,3,true);

        // Necesito un botón que me calcule los días. Algo parecido a como justifico las faltas.
        // De esta manera no tengo un id al que asignar nada. Con el botón sobre el blade sí, porque estoy situado en un registro.
        // $diferencia=Absenteeism::diferenciaDias($absenteeisms->discharge_date,$absenteeisms->withdrawal_date);
        /* 
        $holidays_days=$use->days_holidays;
        $absenteeism_days=$use->absenteeism; 
        */
        //dd($users);
        return view('absenteeism/index', compact('use', 'absenteeism','absenteeisms', 'absences', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $absences = Absence::all();

        return view('absenteeism/solicitud', compact('absences'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AbsenteeismsRequest $req)
    {
        $user = Auth::user();
        if($req->absenteeism_days){
        $absenteeism = Absenteeism::create([
            'withdrawal_date'   => $req->withdrawal_date,
            'discharge_date'    => $req->discharge_date,
            'absenteeism_days'  => $req->absenteeism_days,
            // 'holidays_days'     => $req->holidays_days,
            'id_absence'        => $req->id_absence,
            'id_worker'         => $user->id,
        ]);
        }
        else{
            $absenteeism = Absenteeism::create([
                'withdrawal_date'   => $req->withdrawal_date,
                'discharge_date'    => $req->discharge_date,
                // 'absenteeism_days'  => $req->absenteeism_days,
                'holidays_days'     => $req->holidays_days,
                'id_absence'        => $req->id_absence,
                'id_worker'         => $user->id,
            ]);
        }
        
        if($absenteeism)
            return redirect()->route('absenteeisms.index')->with('message', ['success', __("Solicitud procesada con éxito")]);
        else
            return back()->with('message', ['danger', __("Error al enviar la solicitud")]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $absenteeism = Absenteeism::find($id);
        $user = Auth::user();
        if($user->id == $absenteeism->id_worker){
            return back()->with('message', ['danger', __("Error al justificar la falta. Debe solicitar su justificación a otro supervisor que no sea usted.")]);
        }
        //  Modificando marcaje
        if($request->justify==null){
            return back()->with('message', ['danger', __("Error al justificar la falta. Debe poner el interruptor en ON.")]);
        };
        $absenteeism->update([
            'justify' => $request->justify,
        ]);
        $absenteeism->save();

        return back()->with('message', ['info', __("Falta justificada correctamente")]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $absenteeism = Absenteeism::find($id);
        $absenteeism->delete();
        //Borrado con funcion boot en el modelo. En el que se borran las imagenes que tiene el anuncio.
        if($absenteeism)
            return back()->with('message', ['success', __("Registro suprimido con éxito")]);
        else
            return back()->with('message', ['danger', __("Error al borrar el registro")]);
    }
}
