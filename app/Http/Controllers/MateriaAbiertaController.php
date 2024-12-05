<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\MateriaAbierta;
use App\Models\Periodo;
use Illuminate\Http\Request;

class MateriaAbiertaController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todos los periodos y carreras
        $periodos = Periodo::all();
        $carreras = Carrera::all();

        // Filtrar los datos si se seleccionaron un periodo y una carrera
        $idPeriodo = $request->get('idperiodo', session('periodo_id', '-1'));
        $idCarrera = $request->get('idcarrera', session('carrera_id', '-1'));

        // Almacenar las selecciones en la sesión
        session(['periodo_id' => $idPeriodo, 'carrera_id' => $idCarrera]);

        // Obtener la carrera seleccionada y las retículas de esa carrera
        $carrera = null;
        $materias = collect();

        if ($idCarrera != '-1') {
            $carrera = Carrera::with(['reticulas.materias' => function ($query) use ($idPeriodo) {
                // Obtener solo las materias del periodo seleccionado
                $query->whereHas('materias.periodos', function ($query) use ($idPeriodo) {
                    $query->where('id', $idPeriodo);
                });
            }])->find($idCarrera);

            // Filtrar las materias por la carrera y periodo
            if ($carrera) {
                foreach ($carrera->reticulas as $reticula) {
                    $materias = $materias->merge($reticula->materias->where('periodo_id', $idPeriodo));
                }
            }
        }

        return view('MateriasA.index', compact('periodos', 'carreras', 'carrera', 'materias'));
    }

    public function store(Request $request)
    {
        // Obtener el periodo y la carrera desde la sesión
        $idPeriodo = session('periodo_id');
        $idCarrera = session('carrera_id');

        // Validación para asegurarse de que las materias estén seleccionadas
        $this->validate($request, [
            'materias' => 'required|array|min:1',
            'materias.*' => 'exists:materias,id',
        ]);

        // Almacenar las materias seleccionadas en la tabla MateriaAbierta
        foreach ($request->materias as $idMateria) {
            $existe = MateriaAbierta::where('idPeriodo', $idPeriodo)
                ->where('idCarrera', $idCarrera)
                ->where('idMateria', $idMateria)
                ->exists();

            // Si la materia no está registrada, crear un nuevo registro
            if (!$existe) {
                MateriaAbierta::create([
                    'idPeriodo' => $idPeriodo,
                    'idCarrera' => $idCarrera,
                    'idMateria' => $idMateria,
                ]);
            }
        }

        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('MateriasA.index')
                         ->with('success', 'Materias abiertas guardadas correctamente.');
    }
}
