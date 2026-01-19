<?php


namespace Sitedigitalweb\Renault\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Input;
use Illuminate\Support\Facades\Hash;

class RenaultController extends Controller
{
    /**
     * Mostrar página de trámites
     */
    public function showTramites()
    {
        // Obtener datos del usuario desde la sesión
        $userData = Session::get('validated_user');
        
        if (!$userData) {
            return redirect()->route('home')
                ->with('error', 'Sesión no válida. Por favor, valide su cédula nuevamente.');
        }

        return view('renault::tramites', [
            'user' => $userData
        ]);
    }


public function crear(){
 
 $password = Input::get('password');
 $remember = Input::get('_token');
 $user = new \Sitedigitalweb\Usuario\Tenant\Usuario;    

 $user->name = Input::get('name');
 $user->last_name = Input::get('last_name');
 $user->last_name_second = Input::get('last_name_second');
 $user->suscriptor = Input::get('suscriptor');
 $user->tipo_persona = Input::get('tipo_persona');
 $user->email = Input::get('email');
 $user->address = Input::get('address');
 $user->tipo_documento = Input::get('tipo_documento');
 $user->phone = Input::get('phone');;
 $user->cedula = Input::get('cedula');;
 $user->rol_id = Input::get('level');
 $user->remember_token = Input::get('_token');
 $user->password = Hash::make($password);
 $user->remember_token = Hash::make($remember);
 $user->save();
 return Redirect('renault/usuarios')->with('status', 'ok_create');
}  


}