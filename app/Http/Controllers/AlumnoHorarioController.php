<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Periodo;
use App\Models\GrupoHorario;
use Illuminate\Http\Request;
use App\Models\AlumnoHorario;
use Illuminate\Support\Facades\DB;

class AlumnoHorarioController extends Controller
{
    public $val;

    public function __construct(){
        $this->val=[
            'noctrl'        =>['required','min:8'],
            'idHorarios'    =>['required'],
        ];
    }
    
    public function index()
    {
        $alumnoHorarios= AlumnoHorario::paginate(5);
        return view("AlumnoHorarios.index",compact("alumnoHorarios"));
    }

    public function create()
    {
        $alumnoHorarios= AlumnoHorario::paginate(5); 
        $alumnoHorario=new AlumnoHorario;
        $alumnos=Alumno::all();
        $grupohorarios=GrupoHorario::with([
            'grupo.materia.reticula.carrera' 
        ])->get();
        $grupohorariosperi=GrupoHorario::with([
            'grupo.periodo' 
        ])->get();
        $grupohorariospers=GrupoHorario::with([
            'grupo.personal' 
        ])->get();
        $accion='C';
        $txtbtn='Guardar';
        $des='';
        return view("AlumnoHorarios/form",compact("alumnoHorarios",'alumnoHorario',"accion",'txtbtn','des','alumnos','grupohorarios','grupohorariosperi','grupohorariospers'));
    }

    public function store(Request $request)
    {
        $val= $request->validate($this->val);
        AlumnoHorario::create($val);
        return redirect()->route('AlumnoHorarios.index')->with("mensaje",'se inserto correctamente.');
    }
    
    public function show(AlumnoHorario $alumnoHorario)
    {
        $alumnoHorarios=AlumnoHorario::Paginate(5);
        $accion='D';
        $txtbtn='confirmar la eliminacion';
        $alumnos= [Alumno::find($alumnoHorario->noctrl)];
        $grupohorarios= GrupoHorario::with([
            'grupo.materia.reticula.carrera'
        ])->where('idHorarios', $alumnoHorario->idHorarios)->get();
        $grupohorariosperi= GrupoHorario::with([
            'grupo.periodo'
        ])->where('idHorarios', $alumnoHorario->idHorarios)->get();
        $grupohorariospers= GrupoHorario::with([
            'grupo.personal'
        ])->where('idHorarios', $alumnoHorario->idHorarios)->get();
        $des='disabled';
        return view("AlumnoHorarios.form",compact('alumnoHorarios','alumnoHorario','accion','txtbtn','des','alumnos','grupohorarios','grupohorariosperi','grupohorariospers'));
    }

    public function edit(AlumnoHorario $alumnoHorario)
    {
        $alumnos = Alumno::all();
        $grupohorarios=GrupoHorario::with([
            'grupo.materia.reticula.carrera' 
        ])->get();
        $grupohorariosperi=GrupoHorario::with([
            'grupo.periodo' 
        ])->get();
        $grupohorariospers=GrupoHorario::with([
            'grupo.personal' 
        ])->get();
        $alumnoHorarios = AlumnoHorario::paginate(5);
        
        $accion = 'E';
        $txtbtn = 'actualizar';
        $des = '';

        return view("AlumnoHorarios.form", compact('alumnoHorarios', 'alumnoHorario', 'accion', 'txtbtn', 'des','alumnos','grupohorarios','grupohorariosperi','grupohorariospers'));
    }

    public function update(Request $request, AlumnoHorario $alumnoHorario)
    {
        $val= $request->validate($this->val);
        $alumnoHorario->update($val);
        return redirect()->route('AlumnoHorarios.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AlumnoHorario $alumnoHorario)
    {
        $alumnoHorario->delete();
        return redirect()->route('AlumnoHorarios.index');
    }

    public function showAlumnoHorario()
    {
        $periodos = Periodo::all();
        return view('verhorarioalumno', compact('periodos'));
    }

    public function verHorarioAlumno(Request $request)
    {
        $noctrl = session('user')->noctrl;
        $idPeriodo = $request->input('periodo');
        
        $alumnoHorarios = DB::table('alumno_horarios')
        ->join('alumnos', 'alumno_horarios.noctrl', '=', 'alumnos.noctrl')
        ->join('grupo_horarios', 'alumno_horarios.idHorarios', '=', 'grupo_horarios.idHorarios')
        ->join('grupos', 'grupo_horarios.idGrupo', '=', 'grupos.idGrupo')
        ->join('lugars', 'grupo_horarios.idLugar', '=', 'lugars.idLugar')
        ->join('periodos', 'grupos.idPeriodo', '=', 'periodos.idPeriodo')
        ->join('materias', 'grupos.idMateria', '=', 'materias.idMateria')
        ->join('reticulas', 'materias.idReticula', '=', 'reticulas.idReticula')
        ->join('carreras', 'reticulas.idCarrera', '=', 'carreras.idCarrera')
        ->join('personals', 'grupos.idPersonal', '=', 'personals.idPersonal')
        ->where('alumnos.noctrl', '=', $noctrl)
        ->where('periodos.idPeriodo', '=', $idPeriodo)
        ->select(
            'alumnos.noctrl',
            'alumnos.nombre',
            'alumnos.apellidoP',
            'alumnos.apellidoM',
            'periodos.periodo',
            'carreras.nombreCarrera',
            'reticulas.descripcion',
            'materias.idMateria',
            'materias.nombreMateria',
            'personals.nombre AS personal_nombre',
            'personals.apellidoP AS personal_apellidoP',
            'personals.apellidoM AS personal_apellidoM',
            'grupos.grupo',
            'materias.creditos',
            'alumno_horarios.semestre',
            // Concatenación de horarios y lugares por día
            DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN grupo_horarios.dia = 'Lunes' THEN grupo_horarios.hora END ORDER BY grupo_horarios.hora SEPARATOR ', ') AS lunes_horas"),
            DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN grupo_horarios.dia = 'Martes' THEN grupo_horarios.hora END ORDER BY grupo_horarios.hora SEPARATOR ', ') AS martes_horas"),
            DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN grupo_horarios.dia = 'Miércoles' THEN grupo_horarios.hora END ORDER BY grupo_horarios.hora SEPARATOR ', ') AS miercoles_horas"),
            DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN grupo_horarios.dia = 'Jueves' THEN grupo_horarios.hora END ORDER BY grupo_horarios.hora SEPARATOR ', ') AS jueves_horas"),
            DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN grupo_horarios.dia = 'Viernes' THEN grupo_horarios.hora END ORDER BY grupo_horarios.hora SEPARATOR ', ') AS viernes_horas"),
            
            DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN grupo_horarios.dia = 'Lunes' THEN lugars.nombreCorto END ORDER BY grupo_horarios.hora SEPARATOR ', ') AS lunes_lugar"),
            DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN grupo_horarios.dia = 'Martes' THEN lugars.nombreCorto END ORDER BY grupo_horarios.hora SEPARATOR ', ') AS martes_lugar"),
            DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN grupo_horarios.dia = 'Miércoles' THEN lugars.nombreCorto END ORDER BY grupo_horarios.hora SEPARATOR ', ') AS miercoles_lugar"),
            DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN grupo_horarios.dia = 'Jueves' THEN lugars.nombreCorto END ORDER BY grupo_horarios.hora SEPARATOR ', ') AS jueves_lugar"),
            DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN grupo_horarios.dia = 'Viernes' THEN lugars.nombreCorto END ORDER BY grupo_horarios.hora SEPARATOR ', ') AS viernes_lugar")
        )
        ->groupBy(
            'alumnos.noctrl',
            'alumnos.nombre',
            'alumnos.apellidoP',
            'alumnos.apellidoM',
            'periodos.periodo',
            'carreras.nombreCarrera',
            'reticulas.descripcion',
            'materias.idMateria',
            'materias.nombreMateria',
            'personals.nombre',
            'personals.apellidoP',
            'personals.apellidoM',
            'grupos.grupo',
            'materias.creditos',
            'alumno_horarios.semestre'
        )
        ->paginate(10);
        $periodos = Periodo::all();

        return view('verhorarioalumno', compact('periodos', 'alumnoHorarios', 'idPeriodo'));
    }
}
