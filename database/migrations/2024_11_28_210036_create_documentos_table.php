<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosTable extends Migration
{
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('curp')->nullable();
            $table->string('acta_nacimiento')->nullable();
            $table->string('titulo_preparatoria')->nullable();
            $table->string('noctrl'); // Agregar la columna noctrl
            $table->timestamps();

            // Relación con la tabla alumnos (clave foránea)
            $table->foreign('noctrl')->references('noctrl')->on('alumnos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('documentos');
    }
}
