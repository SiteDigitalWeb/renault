<?php

namespace Sitedigitalweb\Renault\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Sitedigitalweb\Renault\User; // Cambia esto si tu modelo User está en otro lugar
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de validación de cédula
     */
    public function showValidationForm()
    {
        // Si ya está validado, redirigir a trámites
        if (Session::has('validated_cedula')) {
            return redirect()->route('renault.tramites');
        }

        return view('renault::validate');
    }

    /**
     * Validar cédula del usuario
     */
    public function validateCedula(Request $request)
    {
        // Validar formato de cédula
        $validator = Validator::make($request->all(), [
            'cedula' => 'required|digits_between:6,12|numeric'
        ], [
            'cedula.required' => 'El número de cédula es obligatorio',
            'cedula.digits_between' => 'La cédula debe tener entre 6 y 12 dígitos',
            'cedula.numeric' => 'La cédula debe contener solo números'
        ]);

        if ($validator->fails()) {
            return redirect()->route('home')
                ->withErrors($validator)
                ->withInput();
        }

        $cedula = $request->cedula;
        
        // Buscar usuario en la base de datos
        // IMPORTANTE: Cambia esto según tu modelo User
        $user = User::where('cedula', $cedula)
                    ->where('activo', true)
                    ->first();

        if (!$user) {
            return redirect()->route('home')
                ->with('error', 'La cédula ingresada no está habilitada para acceder al sistema.')
                ->withInput();
        }

        // Guardar en sesión
        Session::put('validated_cedula', $cedula);
        Session::put('validated_user', [
            'cedula' => $user->cedula,
            'nombre_completo' => $user->nombre_completo,
            'email' => $user->email
        ]);
        Session::put('validated_at', now()->timestamp);

        // Redirigir a la ruta de trámites
        return redirect()->route('renault.tramites')
            ->with('success', '¡Bienvenido ' . $user->nombre_completo . '!');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        // Limpiar sesión
        Session::forget(['validated_cedula', 'validated_user', 'validated_at']);
        
        return redirect()->route('home')
            ->with('info', 'Sesión cerrada exitosamente');
    }
}