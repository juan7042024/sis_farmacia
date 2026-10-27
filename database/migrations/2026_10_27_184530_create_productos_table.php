<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// asi se creo "php artisan make:model productos -mcr"
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // EN LAS MIGRACIONES "2026_10_27_184530_create_productos_table.php", de este valor "184530" DEBEN ESTAR PRIMERO encima de
        // este modelo que tienen las llaves foraneas, se tienen que crear encima la migracion de "categorias" y de "laboratorios", para 
        // que productos que tiene la llave foranea, no tenga error al crearse ya que estaran creadas los otros 2 modelos que requiere productos
        

        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            // relacion de la llave foranea de "categoria_id", del modelo de categorias en modo restrict para que no borre registros en cascada
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('restrict');
            // relacion de llave foranea de "laboratorio_id", donde puede ser nulo dicha llave forana, no todos los productos tendrian un laboratorio
            $table->foreignId('laboratorio_id')->nullable()->constrained('laboratorios')->onDelete('set null');

            $table->string('codigo_producto', 50)->nullable()->unique();
            $table->string('codigo_barra', 50)->nullable()->unique();
            $table->string('nombre_comercial',255);
            $table->string('nombre_generico', 255);
            $table->string('forma_farmaceutica',100)->nullable();
            $table->string('presentacion', 100)->nullable();
            $table->string('concentracion', 100)->nullable();
            $table->string('accion_terapeutica', 255)->nullable();
            $table->string('unidad_medida', 50)->nullable();
            $table->boolean('usa_receta')->default(false);
            $table->string('imagen', 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
