<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materias_abiertas', function (Blueprint $table) {
            $table->string('idMateriaAbierta')->primary();
            $table->string('idMateria');
            $table->string('idPeriodo');
            $table->string('idCarrera');

            $table->foreign('idMateria')->references('idMateria')->on('materias');
            $table->foreign('idPeriodo')->references('idPeriodo')->on('periodos'); 
            $table->foreign('idCarrera')->references('idCarrera')->on('carreras'); 

        

           
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materias_abiertas');
    }
};
