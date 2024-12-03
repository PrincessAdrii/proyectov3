<?php
 namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Periodo;
use Illuminate\Http\Request;
use App\Models\MateriaAbierta;



class MateriaAbiertaController extends Controller
{

    public $carrera;
    public $ma;
    public $idPeriodo;
    public $idCarrera;

    function __construct()
    {
        if (request()->idperiodo) {
            $this->idPeriodo = request()->idperiodo;
            session(['idPeriodo' => request()->idperiodo]);
        } else {
            $this->idPeriodo = (session()->exists('idPeriodo') ? session('idPeriodo') : "-1");
        }

        if (request()->idcarrera) {
            $this->idCarrera = request()->idcarrera;
            session(['idCarrera' => request()->idcarrera]);
        } else{
            $this->idCarrera = (session()->exists('idCarrera') ? session('idCarrera') : "-1");
        }

        $this->carrera = Carrera::with("reticulas.materias")->where('idCarrera', $this->idCarrera)->get();

        $this->ma = MateriaAbierta::where('idPeriodo', $this->idPeriodo)
            ->where('idCarrera', $this->idCarrera)
            ->get();
    }    

    public function index()
    {
        $periodos = Periodo::all();
        $carreras = Carrera::all();
        $materiasAbiertas = MateriaAbierta::with('materia', 'carrera', 'periodo')
            ->where('idPeriodo', $this->idPeriodo)
            ->where('idCarrera', $this->idCarrera)
            ->get();
    
        return view('MateriasA/index', [
            'periodos' => $periodos,
            'carreras' => $carreras,
            'materiasAbiertas' => $materiasAbiertas,
            'idPeriodo' => $this->idPeriodo,
            'idCarrera' => $this->idCarrera,
        ]);
    }
    

    public function store(Request $request)
{
    foreach ($request->materias as $idMateria) {
        $existe = MateriaAbierta::where('idPeriodo', $this->idPeriodo)
            ->where('idCarrera', $this->idCarrera)
            ->where('idMateria', $idMateria)
            ->exists();

        if (!$existe && $this->idPeriodo != "-1" && $this->idCarrera != "-1") {
            MateriaAbierta::create([
                'idPeriodo' => $this->idPeriodo,
                'idCarrera' => $this->idCarrera,
                'idMateria' => $idMateria,
            ]);
        }
    }

    return redirect()->route('MateriasA.index')->with('success', 'Materias abiertas guardadas.');
}

}