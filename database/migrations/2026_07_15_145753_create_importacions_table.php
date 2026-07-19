<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('importacions', function (Blueprint $table) {

            $table->id();

            $table->string('archivo_original');

            $table->string('archivo_guardado');

            $table->unsignedInteger('cantidad_registros')->default(0);

            $table->unsignedInteger('cantidad_importados')->default(0);

            $table->unsignedInteger('cantidad_conflictos')->default(0);

            $table->enum('estado',[
                'PROCESANDO',
                'FINALIZADA',
                'REVERTIDA',
                'ERROR'
            ])->default('PROCESANDO');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('importacions');
    }
};