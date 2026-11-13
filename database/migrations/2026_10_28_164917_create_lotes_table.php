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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('proveedor_id')->constrained('proveedors')->onDelete('cascade');

            $table->string('nombre', 50)->unique();

            // que sea nullable
            $table->date('fecha_vencimiento')->nullable();
            $table->date('fecha_fabricacion')->nullable();

            // indice que se usa en la base de datos, para filtrar mejor, empezara en orden de izquierda a derecha ese orden
            $table->index(['producto_id', 'proveedor_id', 'nombre']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
