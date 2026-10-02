<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// asi creamos el modelo "php artisan make:model ajuste -mcr" donde "-m", creamos la migracion, "c" el controlador y "r" los metodos del controlador
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ajustes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            // puede estar vacio
            $table->string('descripcion')->nullable();
            $table->string('direccion');
            $table->string('telefono');
            $table->string('email');
            $table->string('divisa');
            // puede estar vacio
            $table->string('logo')->nullable();
            // puede estar vacio, 
            $table->string('web')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajustes');
    }
};
