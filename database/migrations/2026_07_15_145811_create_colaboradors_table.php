<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colaboradors', function (Blueprint $table) {

            $table->id();

            $table->string('documento',30)->unique();

            $table->string('nombre');

            $table->string('cargo')->nullable();

            $table->string('area')->nullable();

            $table->string('jefe')->nullable();

            $table->string('estado')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colaboradores');
    }
};