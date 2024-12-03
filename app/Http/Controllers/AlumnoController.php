<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Models\Alumno;
use App\Models\Carrera;
use Illuminate\Http\Request;
use App\Models\MateriaAbierta;
use Illuminate\Support\Facades\Log;

class AlumnoController extends Controller
{
    public $val;

    public function __construct(){
        $this->val=[
            'noctrl'    =>['required','min:8'],
            'nombre'    =>['required','min:3'],
            'apellidoP' =>['required'],
            'apellidoM' =>['required'],
            'sexo'      =>['required'],
            'email' =>['required'],
            'semestreActual' => ['required'],
            'idCarrera'      =>['required'],

        ];
    }

    public function index()
    {
        
        $alumnos= Alumno::all(); 
        $alumno=new Alumno;
        $carreras=Carrera::all();
        $turnos = Turno::paginate(3);

        $accion='C';
        $txtbtn='Guardar';
        $des='';
        return view("Alumnos2/index",compact("alumnos",'alumno',"accion",'txtbtn','des','carreras','turnos'));
    }

    public function create()
    {
        $alumnos= Alumno::paginate(3); 
        $alumno=new Alumno;
        $carreras=Carrera::all();

        $accion='C';
        $txtbtn='Guardar';
        $des='';
        return view("Alumnos2/form",compact("alumnos",'alumno',"accion",'txtbtn','des','carreras'));
    }

   
    public function store(Request $request)
{
    $accion = 'C';
    $txtbtn = 'Guardar';
    $des = '';
    $carreras = Carrera::all();
    $alumnos = Alumno::paginate(5);
    

    // Validar y crear el alumno
    $validated = $request->validate($this->val);
    $alumno = Alumno::create($validated);

    // Redirigir al formulario de turnos para asignar un horario de inscripción
    return redirect()->route('Turnos.create', ['noctrl' => $alumno->noctrl]);
}

    


 
    public function show(Alumno $alumno)
    {
        $alumnos=Alumno::Paginate(5);
        $accion='D';
        $txtbtn='confirmar la eliminacion';
        $carreras= [Carrera::find($alumno->idCarrera)];
       
        $des='disabled';
        return view("Alumnos2.form",compact('alumnos','alumno','accion','txtbtn','des','carreras'));
    }

  
    // public function edit(Alumno $alumno)
    // {   
    //     // Obtener todas las carreras para mostrarlas en el dropdown
    //     $carreras = Carrera::all();
    //     $alumnos = Alumno::paginate(5);
    //     $turnos = Turno::all();
    //     $accion = 'E';
    //     $txtbtn = 'actualizar';
    //     $des = 'readonly';
    //     $turnos = Turno::paginate(3);
    //     $turno = new Turno();

    //     return view("Alumnos2.form", compact('alumnos', 'alumno', 'accion', 'txtbtn', 'des', 'carreras','turnos','turno'));
    // }

    public function edit(Alumno $alumno)
    {
        // Obtener todas las carreras para el dropdown
        $carreras = Carrera::all();
        $alumnos = Alumno::all();
        // Obtener los turnos paginados
        $turnos = Turno::paginate(3);
    
        // Obtener el turno asociado al alumno (si existe)
        $turno = $alumno->turno ?? new Turno();
    
        // Variables para la vista
        $accion = 'E';
        $txtbtn = 'Actualizar';
        $des = 'readonly';
    
        return view("Alumnos2.form", compact('alumno','alumnos', 'carreras', 'turnos', 'turno', 'accion', 'txtbtn', 'des'));
    }
  
    // public function update(Request $request, Alumno $alumno)
    // {   
       
    //     $val= $request->validate($this->val);
    //     $alumno->update($val);
    //     return redirect()->route('Alumnos.index');
    // }
    public function update(Request $request, Alumno $alumno)
    {
        // Verifica si llega correctamente el alumno
        Log::info('Actualizando alumno:', $alumno->toArray());
    
        // Validar datos del alumno
        $dataAlumno = $request->validate([
            'nombre' => 'required|max:50',
            'apellidoP' => 'required|max:50',
            'apellidoM' => 'required|max:50',
            'email' => 'required|email|max:50',
            'sexo' => 'required|in:M,F',
            'semestreActual' => 'required',
            'idCarrera' => 'required|exists:carreras,idCarrera',
        ]);
    
        // Validar datos del turno
        $dataTurno = $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'inscripcion' => 'required|string|max:50',
            'noctrl' => 'required',
        ]);
    
        // Actualiza los datos del alumno
        $alumno->update($dataAlumno);
    
        // Buscar el turno asociado al alumno
        $turno = Turno::where('noctrl', $alumno->noctrl)->first();
    
        if ($turno) {
            // Verificar si la combinación de fecha y hora ya existe en otro turno
            $turnoExistente = Turno::where('fecha', $dataTurno['fecha'])
                                   ->where('hora', $dataTurno['hora'])
                                   ->where('idTurno', '!=', $turno->idTurno) // Excluir el turno actual
                                   ->first();
    
            if ($turnoExistente) {
                return redirect()->back()
                                 ->with('error', 'La combinación de fecha y hora ya está asignada a otro turno. Por favor, elija otra.')
                                 ->withInput(); // Mantener los datos ingresados
            }
    
            // Actualizar el turno existente
            $turno->update($dataTurno);
    
            return redirect()->route('Alumnos.index')
                             ->with('mensaje', 'Alumno y turno actualizados correctamente.');
        } else {
            return redirect()->route('Alumnos.index')
                             ->with('error', 'No se encontró un turno asignado para este alumno.');
        }
    }
    

 
   
    public function destroy(Alumno $alumno)
{
    $alumno->delete();
    return redirect()->route('Alumnos.index');
}



}
