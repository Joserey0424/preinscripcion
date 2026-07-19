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
        Schema::table('importacions', function (Blueprint $table) {

            $table->string('lider_nombre')->after('archivo_guardado');

            $table->string('lider_documento')->after('lider_nombre');

            $table->text('observaciones')->nullable()->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('importacions', function (Blueprint $table) {

            $table->dropColumn([
                'lider_nombre',
                'lider_documento',
                'observaciones'
            ]);
        });
    }
};
