<?php

namespace App\Http\Controllers;
use App\Models\Grupo;
use App\Models\alumnos_clase;
use App\Models\AlumnosClase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AlumnosClaseController extends Controller
{
    public $val;

    public function __construct()
    {
        $this->val = [
            'noctrl'    => ['required', 'string', 'min:8', 'max:8'],
            'idGrupo'   => ['required', 'string', 'min:1'],
            'calificacion' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }

    /**
     * Muestra todos los grupos.
     */
    public function index()
    {
        // Obtener el ID del docente autenticado desde la sesión
        $idPersonal = session('docente')->idPersonal;
    
        // Filtrar los grupos asignados al docente
        $grupos = Grupo::where('idPersonal', $idPersonal)->get();
    
        return view('docentes', compact('grupos'));
    }
    
    /**
     * Devuelve los alumnos de un grupo específico.
     */
    public function getAlumnos($idGrupo)
{
    $idPersonal = session('docente')->idPersonal;

    // Verificar que el grupo pertenece al docente autenticado
    $grupo = Grupo::where('idGrupo', $idGrupo)
                  ->where('idPersonal', $idPersonal)
                  ->with('alumnosClases.alumno')
                  ->first();

    if (!$grupo) {
        return response()->json(['error' => 'El grupo seleccionado no está asignado a este docente'], 403);
    }

    return response()->json([
        'grupo' => $grupo->grupo,
        'alumnos' => $grupo->alumnosClases->map(function ($alumnoClase) {
            return [
                'id' => $alumnoClase->idClases,
                'noctrl' => $alumnoClase->noctrl,
                'nombre' => $alumnoClase->alumno->nombre ?? 'Sin Nombre',
                'calificacion' => $alumnoClase->calificacion,
            ];
        }),
    ]);
}


    /**
     * Actualiza las calificaciones de los alumnos en el grupo.
     */
    public function update(Request $request, $idGrupo)
    {
        try {
            // Validar datos
            $request->validate([
                'calificaciones' => 'required|array',
                'calificaciones.*' => 'numeric|min:0|max:100',
            ]);

            // Guardar las calificaciones
            foreach ($request->calificaciones as $id => $calificacion) {
                $alumnoClase = AlumnosClase::findOrFail($id);
                $alumnoClase->calificacion = $calificacion;
                $alumnoClase->save();
            }

            return redirect()->route('docentes.index')->with('success', 'Calificaciones guardadas correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al guardar calificaciones: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al guardar las calificaciones.']);
        }
    }

    


    /**
     * Guarda las calificaciones de los alumnos en el grupo (AJAX).
     */
    
     public function guardarCalificaciones(Request $request, $idGrupo)
     {
         try {
             // Validar entrada
             $request->validate([
                 'calificaciones' => 'required|array',
                 'calificaciones.*' => 'numeric|min:0|max:100',
             ]);
     
             // Procesar cada calificación
             foreach ($request->calificaciones as $id => $calificacion) {
                 $alumnoClase = AlumnosClase::findOrFail($id); // Asegúrate que el modelo es `AlumnosClase`
                 $alumnoClase->calificacion = $calificacion;
                 $alumnoClase->save();
             }
     
             return response()->json(['success' => 'Calificaciones guardadas correctamente.']);
         } catch (\Exception $e) {
             Log::error('Error al guardar calificaciones:', ['error' => $e->getMessage()]);
             return response()->json(['error' => 'Ocurrió un error al guardar las calificaciones.'], 500);
         }
     }
     
     
     
     
     
    
}
