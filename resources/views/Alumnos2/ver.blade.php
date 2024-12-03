
@extends("plantilla2")

@section("contenido1")

@include("Alumnos2/tabla")
@endsection

@section("contenido2")

    <h1>Aqui va mi formulario html de insertar</h1>
@endsection

@section("contenido3")
<h1>Ver</h1>
<form action="{{route('Alumnos.eliminar', $alumno)}}" method="POST">
  @csrf
    <div class="row mb-3">
      <label for="nombre"  class="col-sm-2 col-form-label">Nombre</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="nombre" name="nombre" disabled value="{{$alumno->nombre}}">
      </div>
    </div>

    <div class="row mb-3">
        <label for="apellidoP"  class="col-sm-2 col-form-label">Apellido Paterno</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="nombre" name="apellidoP" disabled value="{{$alumno->apellidoP}}">
        </div>
      </div>

      <div class="row mb-3">
        <label for="email"  class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
          <input type="email" class="form-control" id="nombre" name="email" disabled value="{{$alumno->email}}">
        </div>
      </div>

    


     <button  type="submit" class="btn btn-primary">Está usted seguro??</button>
    
    
     <a href="{{route("alumnos.index")}}" class="btn btn-primary">regresar</a>
  </form>
  @endsection