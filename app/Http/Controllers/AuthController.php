<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function iniciosA(Request $request)
    {
        $noctrl = $request->input('noctrl');
        $nip = $request->input('nip');

        // Consulta la tabla AlumnoUsuario
        $user = DB::table('alumno_usuarios')->where('noctrl', $noctrl)->where('nip', $nip)->first();

        if ($user) {
            $alumno = DB::table('alumnos')
                ->where('noctrl', $user->noctrl)
                ->first();
            $turno = DB::table('turnos')
                ->where('noctrl', $user->noctrl)
                ->orderBy('created_at', 'desc') // Ordenar por created_at
                ->first();

            // Guardar los datos del usuario y del alumno en la sesión
            session([
                'user' => $user,
                'alumno' => $alumno, // Contiene nombre y apellidos
                'turno' => $turno,
            ]);
            return redirect('/verhorarioalumno'); // Redirigir al inicio
        }

        // Credenciales incorrectas
        return back()->withErrors(['iniciosA' => 'Número de control o NIP incorrectos']);
    }

    public function cerrarA()
    {
        // Cerrar sesión
        session()->forget('user');
        return redirect('/verhorarioalumno');
    }

    public function registroA(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'noctrl' => 'required',
            'nip' => 'required'
        ]);

        // Insertar en la tabla AlumnoUsuario
        DB::table('alumno_usuarios')->insert([
            'noctrl' => $request->input('noctrl'),
            'nip' => $request->input('nip'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = DB::table('alumno_usuarios')->where('noctrl', $request->input('noctrl'))->first();

        // Guardar al usuario en la sesión
        session(['user' => $user]);

        // Redirigir al login con mensaje de éxito
        return redirect('/verhorarioalumno')->with('success', 'Registro completado. Ahora puedes iniciar sesión.');
    }
}