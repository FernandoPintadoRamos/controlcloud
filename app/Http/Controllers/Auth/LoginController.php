<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        /*
        if(Auth::check()){
            Auth::logoutOtherDevices($request->input('password'));
        }
        */
    }

    protected function sendLoginResponse(Request $request)
    {
        // Cerramos sesiones que puedan haber en otros puestos
        $request->session()->regenerate();
        $user = Auth::User();
        $previous_session = $user->session_id;
        if ($previous_session) {
            Session::getHandler()->destroy($previous_session);
        }

        $user->session_id = Session::getId();
        $user->is_logged = false;
        $user->save();
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }


    protected function authenticated(Request $request, $user) {
        if($user->is_logged){
            //dd($this->guard());
            $this->guard()->logout(); // Cerramos la sesión 
            $request->session()->invalidate(); // Invalidamos la sesión 
            session()->flash('message', ['danger', 'Ya hay un usuario logueado con esta cuenta']); 
            return redirect('/login');
        } 
        else{
            //dd($this->guard());
            $user->is_logged = true;
            $user->save(); 
        }

        // Y enviamos al usuario a la página de “home” si ha iniciado sesión
        return redirect($this->redirectPath()); 
    }

    public function logout(Request $request)
    {
        $user = User::find(auth()->id());
        $user->is_logged = false;
        $user->save();
 

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/login');
    }

    protected function loggedOut(Request $request)
    {
        session()->flash('message', ['success', 'Has cerrado sesión correctamente']);
        return redirect('/login');
    }
}
