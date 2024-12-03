<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocenteAuthController extends Controller
{
    /**
     * Inicio de sesión para docentes.
     */
    public function inicios(Request $request)
    {
        $idPersonal = $request->input('idPersonal');
        $nip = $request->input('nip');

        // Consulta la tabla docente_usuarios
        $user = DB::table('docente_usuarios')
            ->where('idPersonal', $idPersonal)
            ->where('nip', $nip)
            ->first();

        if ($user) {
            // Consulta los datos del docente desde la tabla personals
            $docente = DB::table('personals')
                ->where('idPersonal', $user->idPersonal)
                ->first();

            // Guardar los datos del usuario y docente en la sesión
            session([
                'user' => $user,
                'docente' => $docente, // Contiene nombre y otros datos
            ]);

            return redirect('/docentes'); // Redirigir al dashboard del docente
        }
 
        // Credenciales incorrectas
        return back()->withErrors(['inicios' => 'ID de Personal o NIP incorrectos']);
    }

    /**
     * Cerrar sesión de docente.
     */
    public function cerrar()
    {
        // Cerrar sesión
        session()->forget('user');
        session()->forget('docente');
        return redirect('/docentes');
    }

    /**
     * Registro de un nuevo docente.
     */
    public function registro(Request $request)
{
    // Validación de los datos
    $validated = $request->validate([
        'idPersonal' => 'required',
        'nip' => 'required|min:4',
    ]);

    // Debugging para revisar los datos enviados
    if (!$request->has('idPersonal')) {
        return back()->withErrors(['idPersonal' => 'El campo ID Personal no se envió correctamente.']);
    }

    // Insertar en la tabla docente_usuarios
    DB::table('docente_usuarios')->insert([
        'idPersonal' => $validated['idPersonal'],
        'nip' => $validated['nip'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $user = DB::table('docente_usuarios')->where('idPersonal', $validated['idPersonal'])->first();

    // Guardar al usuario en la sesión
    session(['user' => $user]);

    // Redirigir al login con mensaje de éxito
    return redirect('/docentes')->with('success', 'Registro completado. Ahora puedes iniciar sesión.');
}

}
