<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Models\Alumno;
use App\Models\Documento;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    public function create($noctrl)
{
    $alumno = Alumno::findOrFail($noctrl);
    return view('form', compact('alumno'));
}


    public function store(Request $request, $noctrl)
    {
        $request->validate([
            'curp' => 'required|file|mimes:pdf,jpg,png',
            'acta_nacimiento' => 'required|file|mimes:pdf,jpg,png',
            'titulo_preparatoria' => 'required|file|mimes:pdf,jpg,png',
        ]);

        // Procesar y guardar los archivos
        $documentacion = new Documento();
        $documentacion->noctrl = $noctrl;
        $documentacion->curp = $request->file('curp')->store('documentos');
        $documentacion->acta_nacimiento = $request->file('acta_nacimiento')->store('documentos');
        $documentacion->titulo_preparatoria = $request->file('titulo_preparatoria')->store('documentos');
        $documentacion->save();

        return redirect()->route('pagos')->with('mensaje', 'Documentación guardada correctamente.');
    }
}
