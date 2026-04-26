<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dasbor_kustom', function (Blueprint $table) {
            $table->enum('status_dasbor', ['aktif', 'nonaktif'])
                  ->default('nonaktif')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('dasbor_kustom', function (Blueprint $table) {
            $table->string('status_dasbor')->change();
        });
    }
};