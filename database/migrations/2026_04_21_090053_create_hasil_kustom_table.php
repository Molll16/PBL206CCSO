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
        Schema::create('hasil_kustom', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dasbor_kustom_id')->constrained('dasbor_kustom')->onDelete('cascade');
            $table->foreignId('fitur_id')->constrained('fitur')->onDelete('cascade');
            $table->integer('kolom');
            $table->integer('baris');
            $table->string('status_fitur');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_kustom');
    }
};
