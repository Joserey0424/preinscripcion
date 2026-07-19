<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conflictos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('asignacion_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->enum('tipo',[
                'DOCUMENTO_DUPLICADO',
                'FECHA_DIFERENTE',
                'YA_EXISTE'
            ]);

            $table->text('descripcion');

            $table->boolean('resuelto')->default(false);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conflictos');
    }
};