<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignacions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('importacion_id')
                ->constrained('importacions')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('fecha_id')
                ->constrained('fechas')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('documento', 30);

            $table->string('nombre');

            $table->unsignedInteger('fila_excel');

            $table->enum('estado', [
                'OK',
                'DUPLICADO',
                'CONFLICTO'
            ])->default('OK');

            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->timestamp('fecha_importacion')
                ->useCurrent();

            $table->text('observaciones')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignacions');
    }
};
