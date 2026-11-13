<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// asi se creo "php artisan make:model UbicacionFisica -mcr"
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ubicacion_fisicas', function (Blueprint $table) {
            $table->id();
            //llaves foraneas
            $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('cascade');
            $table->string('nombre', 150);
            $table->string('description')->nullable();
            // indice que se usa en la base de datos, para filtrar mejor, empezara en orden de izquierda a derecha ese orden
            $table->index(['sucursal_id', 'nombre']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ubicacion_fisicas');
    }
};
