<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Alumno;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    public function create()
    {
        return view('form'); // Cambia 'documentos.form' por la ruta de tu vista.
    }

    public function store(Request $request)
    {
        $request->validate([
            'curp' => 'required|file|mimes:pdf|max:2048',
            'acta_nacimiento' => 'required|file|mimes:pdf|max:2048',
            'titulo_preparatoria' => 'required|file|mimes:pdf|max:2048',
            'noctrl' => 'required|exists:alumnos,noctrl', // Validar que el noctrl exista en la tabla alumnos
        ]);

        $documentos = [
            'noctrl' => $request->noctrl, // Guardar el número de control
        ];

        // Guardar archivos
        if ($request->hasFile('curp')) {
            $documentos['curp'] = $request->file('curp')->store('documentos', 'public');
        }
        if ($request->hasFile('acta_nacimiento')) {
            $documentos['acta_nacimiento'] = $request->file('acta_nacimiento')->store('documentos', 'public');
        }
        if ($request->hasFile('titulo_preparatoria')) {
            $documentos['titulo_preparatoria'] = $request->file('titulo_preparatoria')->store('documentos', 'public');
        }

        Documento::create($documentos);

        return redirect()->back()->with('success', 'Documentos subidos correctamente.');
    }
}
