
@extends("inicio2")

@section("contenido1")
<!-- Aquí iría contenido general si es necesario -->
@endsection

@section("contenido2")

<form action="" method="post"></form>


<!-- Encabezado general -->
<h4>Información del Alumno</h4>
<p><strong>Número de Control:</strong> {{ $alumno->noctrl }}</p>

<p><strong>Nombre:</strong> {{ $alumno->nombre }} {{ $alumno->apellidoP }} {{ $alumno->apellidoM }}</p>
<p><strong>Semestre:</strong> {{ $alumno->semestreActual }}</p>
<p><strong>Estado:</strong> {{ $alumno->turno->inscripcion }}</p>

<a href="{{ route('Alumnos.editar', $alumno->noctrl) }}" class="btn btn-primary">Actualizar información</a>
<a href="{{ route('pagos.edit', $alumno->noctrl) }}" class="btn btn-secondary">Actualizar pago</a>



@endsection



