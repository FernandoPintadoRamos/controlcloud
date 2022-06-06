<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Agenda;

class AgendaController extends Controller
{
    //
    public function show(){
        $avisos = Agenda::where('usuario_receptor', '=', Auth::user()->id)->where('visto', '=', '0')->get()->toArray();

        return view('agenda.avisos', compact('avisos'));
    }

    // mostrarYaVistos

    public function verTodos(){
        $avisos = Agenda::where('usuario_receptor', '=', Auth::user()->id)->get()->toArray();

        return view('agenda.avisos', compact('avisos'));
    }

    public function verLeidos(){
        $avisos = Agenda::where('usuario_receptor', '=', Auth::user()->id)->where('visto', '=', '1')->get()->toArray();

        return view('agenda.avisos', compact('avisos'));
    }

    public function marcarLeido(Request $request){

        $aviso = Agenda::find($request->id_aviso);

        $aviso -> update([
            'visto' => 1,
        ]);

        return back();
    }

    public function marcarNoLeido(Request $request){

        $aviso = Agenda::find($request->id_aviso);

        $aviso -> update([
            'visto' => 0,
        ]);

        return back();
    }
}
