@extends('inicio2')

@section('title', 'Verificación de Docente')

@section('contenido1')
<div class="container mt-4">
    <h1 class="text-center mb-4">Gestión de Calificaciones</h1>

    <!-- Información del Docente -->
    <div>
        <h1> Docente</h1>

        
    </div>

    <!-- Selección de Grupo -->
    <div class="mt-4">
        <h5>Seleccionar Grupo</h5>
        <select id="grupoSelect" name="grupo" class="form-control">
            <option value="">Seleccionar Grupo</option>
            @foreach ($grupos as $grupo)
                <option value="{{ $grupo->idGrupo }}">{{ $grupo->grupo }}</option>
            @endforeach
        </select>
        
    </div>

    <!-- Tabla de Alumnos -->
    <div id="alumnosContainer" class="mt-4" style="display: none;">
        <h5>Lista de Alumnos</h5>
        <form id="calificacionesForm" method="POST">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th>Número de Control</th>
                        <th>Nombre</th>
                        <th>Calificación</th>
                    </tr>
                </thead>
                <tbody id="alumnosTableBody">
                    <!-- Se llena con AJAX -->
                </tbody>
            </table>
            <button type="submit" class="btn btn-success w-100" disabled>Guardar Calificaciones</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const grupoSelect = document.getElementById('grupoSelect');
        const alumnosContainer = document.getElementById('alumnosContainer');
        const alumnosTableBody = document.getElementById('alumnosTableBody');
        const calificacionesForm = document.getElementById('calificacionesForm');
        const submitButton = calificacionesForm.querySelector('button[type="submit"]');

        grupoSelect.addEventListener('change', function () {
            const idGrupo = this.value;

            if (idGrupo) {
                // Actualizar la acción del formulario
                calificacionesForm.action = `/docentes/${idGrupo}`;

                // Solicitud AJAX para obtener los alumnos
                fetch(`/docentes/${idGrupo}/alumnos`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        alumnosTableBody.innerHTML = '';
                        if (data.alumnos.length > 0) {
                            data.alumnos.forEach(alumno => {
                                alumnosTableBody.innerHTML += `
                                    <tr>
                                        <td>${alumno.noctrl}</td>
                                        <td>${alumno.nombre}</td>
                                        <td>
                                            <input type="number" name="calificaciones[${alumno.id}]" value="${alumno.calificacion}" class="form-control" min="0" max="100" required>
                                        </td>
                                    </tr>`;
                            });
                            submitButton.disabled = false;
                            alumnosContainer.style.display = 'block';
                        } 
                        else {
                            alumnosTableBody.innerHTML = `
                                <tr>
                                    <td colspan="3">No hay alumnos en este grupo.</td>
                                </tr>`;
                            submitButton.disabled = true;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al cargar los alumnos.');
                    });
            }
        });
    });
</script>
@endsection
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
        padding: 20px;
    }

    .content {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .table thead th {
        background-color: #007bff;
        color: white;
    }

    .btn:disabled {
        background-color: #cccccc;
        border-color: #cccccc;
    }
</style>