<style>
    /* Estilos generales */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 20px;
}

h2, h3 {
    color: #4CAF50;
    font-size: 24px;
    margin-bottom: 20px;
}

/* Estilo del formulario */
.form-container {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    max-width: 500px;
    margin: 0 auto;
}

label,
.input-select{
    font-size: 16px;
    margin-right: 10px;
}

.input-select {
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #ccc;
    width: 100%;
    margin-bottom: 20px;
}

.submit-btn {
    display: block;
    margin: 0 auto;  /* Centra el botón */
    background-color: #3b82f6;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.submit-btn:hover {
    background-color: #3370d2;
}

/* Estilo de la tabla */
.table-responsive {
    overflow-x: auto;
    margin-top: 30px;
    display: flex;
    justify-content: center;  /* Centra horizontalmente */
    align-items: center;      /* Centra verticalmente */
    height: 100vh;
}

/* Tabla con columnas del mismo ancho */
.horario-table {
    width: 70%;
    border-collapse: collapse;
    table-layout: fixed; /* Forzar columnas del mismo ancho */
    margin-top: 20px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.horario-table th, .td {
    padding: 12px 15px;
    text-align: center; /* Centrar horizontalmente */
    vertical-align: middle; /* Centrar verticalmente */
    border: 1px solid #ddd;
    word-wrap: break-word; /* Permite que el texto se ajuste a la celda */
}

.horario-table th {
    background-color: #3b82f6;
    color: white;
    text-align: center;
}

.horario-table tr:nth-child(even) {
    background-color: #f2f2f2;
}

.horario-table tr:hover {
    background-color: #f1f1f1;
}


/* Paginación */
.pagination {
    margin-top: 20px;
    text-align: center;
}

.pagination a {
    color: #4CAF50;
    padding: 8px 16px;
    text-decoration: none;
    margin: 0 5px;
    border-radius: 5px;
}

.pagination a:hover {
    background-color: #4CAF50;
    color: white;
}

.pagination .disabled {
    color: #ddd;
}

.no-horarios {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    padding: 15px;
    border-radius: 5px;
    margin-top: 20px;
    text-align: center;
    font-size: 16px;
    font-weight: bold;
}
</style>
@extends('inicio2')

@section('title', 'Ver Horario Alumno')

@section('contenido1')
    <br>
    <form method="POST" action="{{ route('verhorarioalumno') }}" class="form-container">
        @csrf
        <label for="periodo">Período:</label>
        <select name="periodo" id="periodo" required class="input-select">
            <option value="" disabled selected>Seleccione un período</option>
            @foreach($periodos as $periodo)
                <option value="{{ $periodo->idPeriodo }}" 
                    @if(isset($idPeriodo) && $idPeriodo == $periodo->idPeriodo) selected @endif>
                    {{ $periodo->periodo }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="submit-btn">Enviar</button>
    </form>

    @if(isset($alumnoHorarios) && $alumnoHorarios->count() > 0)
        <div class="table-responsive">
            <table class="horario-table">
                <thead>
                    <tr>
                        <th colspan="2">No Ctrl</th>
                        <th colspan="3">Alumno</th>
                        <th colspan="2">Semestre</th>
                        <th colspan="3">Periodo</th>
                    </tr>
                </thead>
                @php
                    $alumnoHorario = $alumnoHorarios->first();
                @endphp
                <tbody>
                    @if ($alumnoHorario)
                        <tr>
                            <td class="td" colspan="2">{{ $alumnoHorario->noctrl }}</td>
                            <td class="td" colspan="3">{{ $alumnoHorario->nombre }} {{ $alumnoHorario->apellidoP }} {{ $alumnoHorario->apellidoM }}</td>
                            <td class="td" colspan="2">{{ $alumnoHorario->semestre }}</td>
                            <td class="td" colspan="3">{{ $alumnoHorario->periodo }}</td>
                        </tr>
                    @endif
                </tbody>
                <thead>
                    <tr>
                        <th colspan="5">Carrera</th>
                        <th colspan="5">Especialidad</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($alumnoHorario)
                        <tr>
                            <td class="td" colspan="5">{{ $alumnoHorario->nombreCarrera }}</td>
                            <td class="td" colspan="5">{{ $alumnoHorario->descripcion }}</td>
                        </tr>
                    @endif
                </tbody>
                <thead>
                    <tr>
                        <th colspan="3">Materia</th>
                        <th colspan="1">Grupo</th>
                        <th colspan="1">Créditos</th>
                        <th colspan="1">L</th>
                        <th colspan="1">M</th>
                        <th colspan="1">X</th>
                        <th colspan="1">J</th>
                        <th colspan="1">V</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alumnoHorarios as $alumnoHorario)
                        <tr>
                            <td colspan="3">
                                <b>{{ $alumnoHorario->idMateria }}</b> <br>
                                {{ $alumnoHorario->nombreMateria }} <br>
                                {{ $alumnoHorario->personal_nombre }} {{ $alumnoHorario->personal_apellidoP }} {{ $alumnoHorario->personal_apellidoM }}
                            </td>
                            <td class="td" colspan="1"> 
                                <b>{{ $alumnoHorario->grupo }}</b>
                            </td>
                            <td class="td" colspan="1"> 
                                {{ $alumnoHorario->creditos }}
                            </td>
                            @if($alumnoHorario->lunes_horas && $alumnoHorario->lunes_lugar)
                                <td class="td" colspan="1">
                                    {{ $alumnoHorario->lunes_horas }} - {{ $alumnoHorario->lunes_horas+1 }} <br>
                                    {{ $alumnoHorario->lunes_lugar }}
                                </td>
                            @else
                                <td class="td" colspan="1"></td>
                            @endif
                            @if($alumnoHorario->martes_horas && $alumnoHorario->martes_lugar)
                                <td class="td" colspan="1">
                                    {{ $alumnoHorario->martes_horas }} - {{ $alumnoHorario->martes_horas+1 }}<br>
                                    {{ $alumnoHorario->martes_lugar }}
                                </td>
                            @else
                                <td class="td" colspan="1"></td>
                            @endif
                            @if($alumnoHorario->miercoles_horas && $alumnoHorario->miercoles_lugar)
                                <td class="td" colspan="1">
                                    {{ $alumnoHorario->miercoles_horas }} - {{ $alumnoHorario->miercoles_horas+1 }}<br>
                                    {{ $alumnoHorario->miercoles_lugar }}
                                </td>
                            @else
                                <td class="td" colspan="1"></td>
                            @endif
                            @if($alumnoHorario->jueves_horas && $alumnoHorario->jueves_lugar)
                                <td class="td" colspan="1">
                                    {{ $alumnoHorario->jueves_horas }} - {{ $alumnoHorario->jueves_horas+1 }}<br>
                                    {{ $alumnoHorario->jueves_lugar }}
                                </td>
                            @else
                                <td class="td" colspan="1"></td>
                            @endif
                            @if($alumnoHorario->viernes_horas && $alumnoHorario->viernes_lugar)
                                <td class="td" colspan="1">
                                    {{ $alumnoHorario->viernes_horas }} - {{ $alumnoHorario->viernes_horas+1 }}<br>
                                    {{ $alumnoHorario->viernes_lugar }}
                                </td>
                            @else
                                <td class="td" colspan="1"></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">
            {{ $alumnoHorarios->links() }} <!-- Paginación -->
        </div>
    @elseif(isset($alumnoHorarios) && $alumnoHorarios->count() == 0)
        <p class="no-horarios">No hay horarios disponibles para el período seleccionado.</p>
    @endif
@endsection